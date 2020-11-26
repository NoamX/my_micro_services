const Discussion = require("../models/discussion.model");
const handleError = require("../utils/handleError");

const DiscussionController = {
  create: (req, res) => {
    const { name, users } = req.body;

    const discussion = new Discussion({
      name: name,
      users: users,
    });

    if (!users || (Array.isArray(users) && users.length < 2)) {
      return res
        .status(422)
        .contentType("application/json")
        .json({
          errors: {
            users: "Users must be 2 and are required.",
          },
        });
    }

    discussion.save((err) => {
      if (err) {
        return res
          .status(422)
          .contentType("application/json")
          .json(handleError(err, "name"));
      }

      return res
        .status(201)
        .contentType("application/json")
        .json({ discussion, status: "Created." });
    });
  },
  read: (req, res) => {
    const { id } = req.params;

    Discussion.findById(id)
      .then((discussion) => {
        if (!discussion) {
          return res
            .status(200)
            .contentType("application/json")
            .json({ err: "No discussion found." });
        }

        return res
          .status(200)
          .contentType("application/json")
          .json({ discussion });
      })
      .catch(() => {
        return res
          .status(400)
          .contentType("application/json")
          .json("Bad Request.");
      });
  },
  update: (req, res) => {
    const { id } = req.params;
    const { name, users } = req.body;

    if (!users || (Array.isArray(users) && users.length < 2)) {
      return res
        .status(422)
        .contentType("application/json")
        .json({
          errors: {
            users: "Users must be 2 and are required.",
          },
        });
    }

    Discussion.findByIdAndUpdate(id, { name, users }, { new: true })
      .then((discussion) => {
        if (!discussion) {
          return res
            .status(200)
            .contentType("application/json")
            .json({ err: "No discussion found." });
        }

        return res
          .status(200)
          .contentType("application/json")
          .json({ discussion, status: "Updated." });
      })
      .catch(() => {
        return res
          .status(400)
          .contentType("application/json")
          .json("Bad Request.");
      });
  },
  remove: (req, res) => {
    const { id } = req.params;

    Discussion.findByIdAndDelete(id)
      .then((discussion) => {
        if (!discussion) {
          return res
            .status(200)
            .contentType("application/json")
            .json({ err: "No discussion found." });
        }

        return res
          .status(200)
          .contentType("application/json")
          .json({ discussion, status: "Deleted." });
      })
      .catch(() => {
        return res
          .status(400)
          .contentType("application/json")
          .json("Bad Request.");
      });
  },
};

module.exports = DiscussionController;
