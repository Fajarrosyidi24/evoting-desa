// SPDX-License-Identifier: MIT
pragma solidity ^0.8.19;

contract Voting {

    // ─── STRUCTS ───────────────────────────────────────────

    struct Kandidat {
        uint256 id;
        string nama;
        string visi;
        uint256 jumlahSuara;
        bool aktif;
    }

    struct Pemilih {
        bool terdaftar;
        bool sudahVoting;
        uint256 votedKandidatId;
        uint256 waktuVoting;
    }

    // ─── STATE VARIABLES ───────────────────────────────────

    address public owner;          // panitia/admin
    string  public namaVoting;     // "Pilkades Desa Sukamaju 2025"
    uint256 public startTime;
    uint256 public endTime;
    bool    public votingAktif;

    uint256 private kandidatCount;

    mapping(uint256 => Kandidat) public kandidat;
    mapping(address => Pemilih)  public pemilih;

    // ─── EVENTS ────────────────────────────────────────────

    event KandidatDitambah(uint256 indexed id, string nama);
    event PemilihTerdaftar(address indexed pemilihAddr);
    event SuaraDiberikan(address indexed pemilihAddr, uint256 indexed kandidatId, uint256 waktu);
    event VotingDimulai(uint256 startTime, uint256 endTime);
    event VotingDiakhiri(uint256 waktu);

    // ─── MODIFIERS ─────────────────────────────────────────

    modifier onlyOwner() {
        require(msg.sender == owner, "Hanya admin yang bisa");
        _;
    }

    modifier sedangBerlangsung() {
        require(votingAktif, "Voting tidak aktif");
        require(block.timestamp >= startTime, "Voting belum dimulai");
        require(block.timestamp <= endTime,   "Voting sudah berakhir");
        _;
    }

    // ─── CONSTRUCTOR ───────────────────────────────────────

    constructor(string memory _namaVoting) {
        owner       = msg.sender;
        namaVoting  = _namaVoting;
        votingAktif = false;
    }

    // ─── FUNGSI ADMIN ──────────────────────────────────────

    function tambahKandidat(
        string memory _nama,
        string memory _visi
    ) external onlyOwner {
        require(!votingAktif, "Tidak bisa tambah kandidat saat voting aktif");

        kandidatCount++;
        kandidat[kandidatCount] = Kandidat({
            id          : kandidatCount,
            nama        : _nama,
            visi        : _visi,
            jumlahSuara : 0,
            aktif       : true
        });

        emit KandidatDitambah(kandidatCount, _nama);
    }

    function daftarkanPemilih(address _pemilih) external onlyOwner {
        require(!pemilih[_pemilih].terdaftar, "Pemilih sudah terdaftar");

        pemilih[_pemilih] = Pemilih({
            terdaftar      : true,
            sudahVoting    : false,
            votedKandidatId: 0,
            waktuVoting    : 0
        });

        emit PemilihTerdaftar(_pemilih);
    }

    function mulaiVoting(
        uint256 _durasiMenit
    ) external onlyOwner {
        require(!votingAktif, "Voting sudah aktif");
        require(kandidatCount >= 2, "Minimal 2 kandidat");

        startTime   = block.timestamp;
        endTime     = block.timestamp + (_durasiMenit * 1 minutes);
        votingAktif = true;

        emit VotingDimulai(startTime, endTime);
    }

    function akhiriVoting() external onlyOwner {
        require(votingAktif, "Voting tidak aktif");
        votingAktif = false;
        emit VotingDiakhiri(block.timestamp);
    }

    // ─── FUNGSI VOTING ─────────────────────────────────────

    // Dipanggil oleh backend Laravel (pakai admin wallet)
    // atas nama warga yang sudah diverifikasi
    function vote(
        address _pemilihAddr,
        uint256 _kandidatId
    ) external onlyOwner sedangBerlangsung {
        Pemilih storage p = pemilih[_pemilihAddr];

        require(p.terdaftar,   "Pemilih tidak terdaftar");
        require(!p.sudahVoting, "Pemilih sudah memberikan suara");
        require(kandidat[_kandidatId].aktif, "Kandidat tidak valid");

        p.sudahVoting     = true;
        p.votedKandidatId = _kandidatId;
        p.waktuVoting     = block.timestamp;

        kandidat[_kandidatId].jumlahSuara++;

        emit SuaraDiberikan(_pemilihAddr, _kandidatId, block.timestamp);
    }

    // ─── FUNGSI BACA (VIEW) ────────────────────────────────

    function getKandidat(uint256 _id)
        external view
        returns (uint256, string memory, string memory, uint256)
    {
        Kandidat memory k = kandidat[_id];
        return (k.id, k.nama, k.visi, k.jumlahSuara);
    }

    function getTotalKandidat() external view returns (uint256) {
        return kandidatCount;
    }

    function sudahVoting(address _addr) external view returns (bool) {
        return pemilih[_addr].sudahVoting;
    }

    function getWinner()
        external view
        returns (uint256 winnerId, string memory winnerNama, uint256 suaraTerbanyak)
    {
        require(!votingAktif, "Voting masih berlangsung");

        for (uint256 i = 1; i <= kandidatCount; i++) {
            if (kandidat[i].jumlahSuara > suaraTerbanyak) {
                suaraTerbanyak = kandidat[i].jumlahSuara;
                winnerId       = kandidat[i].id;
                winnerNama     = kandidat[i].nama;
            }
        }
    }

    function sisaWaktu() external view returns (uint256) {
        if (!votingAktif || block.timestamp > endTime) return 0;
        return endTime - block.timestamp;
    }
}