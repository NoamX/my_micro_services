const mongoose = require("mongoose");
const { Schema } = mongoose;

const DiscussionSchema = new Schema(
  {
    userId: {
      type: Number,
      required: [true, "userId required."],
    },
    receiverId: {
      type: Number,
      required: [true, "receiverId required"],
    },
    messageId: {
      type: Number,
      required: [true, "messageId required"],
    },
  },
  { timestamps: true }
);

module.exports = mongoose.model("Discussion", DiscussionSchema);
