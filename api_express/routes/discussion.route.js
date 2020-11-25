const router = require("express").Router();
const {
  create,
  read,
  update,
  remove,
} = require("../controllers/discussion.controller");

router.post("/", (req, res) => create(req, res));
router.get("/", (req, res) => read(req, res));
router.patch("/", (req, res) => update(req, res));
router.delete("/", (req, res) => remove(req, res));

module.exports = router;
