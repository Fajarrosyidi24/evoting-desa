module.exports = (req, res, next) => {
    const secret = req.headers['x-internal-secret'];

    if (secret !== process.env.INTERNAL_SECRET) {
        return res.status(401).json({ error: 'Unauthorized' });
    }

    next();
};