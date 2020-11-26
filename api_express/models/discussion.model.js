const mongoose = require("mongoose");
const { Schema } = mongoose;

const DiscussionSchema = new Schema(
  {
    name: {
      type: String,
      required: [true, "Name required."],
      unique: true,
    },
    users: [
      {
        type: Number,
        required: [true, "Users must be 2 and are required."],
      },
    ],
  },
  { timestamps: true }
);

DiscussionSchema.plugin(require("mongoose-unique-validator"), {
  message: "{VALUE} is already taken.",
});

module.exports = mongoose.model("Discussion", DiscussionSchema);
