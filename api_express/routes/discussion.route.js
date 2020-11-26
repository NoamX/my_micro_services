const router = require("express").Router();
const {
  create,
  read,
  update,
  remove,
} = require("../controllers/discussion.controller");

router.post("/", (req, res) => create(req, res));
router.get("/:id", (req, res) => read(req, res));
router.patch("/:id", (req, res) => update(req, res));
router.delete("/:id", (req, res) => remove(req, res));

module.exports = router;
