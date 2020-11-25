const mongoose = require("mongoose");
const { Schema } = mongoose;

const DiscussionSchema = new Schema(
  {
    //
  },
  { timestamps: true }
);

module.exports = mongoose.model("Discussion", DiscussionSchema);
