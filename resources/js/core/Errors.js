class Errors {
  constructor() {
    this.errors = {};
  }

  any() {
    return Object.keys(this.errors).length > 0;
  }

  get(field) {
    if (this.errors[field]) {
      return this.errors[field][0];
    }
  }

  record(errors) {
    this.errors = errors;
  }

  has(field) {
    return this.errors.hasOwnProperty(field);
  }

  clear(field) {
    if (field) {
      delete this.errors[field];
    }
  }

  reset() {
    this.errors = {};
  }
}

export default Errors;
