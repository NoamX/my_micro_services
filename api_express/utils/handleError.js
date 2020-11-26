const handleError = (err, ...fields) => {
  const errors = {};

  fields.map((field) => {
    if ((error = err.errors[field])) {
      errors[field] = error.message;
    }
  });

  return { errors };
};

module.exports = handleError;
