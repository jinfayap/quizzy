import Errors from "./Errors.js";

class Form {
  constructor(data) {
    this.originalData = data;

    for (let field in data) {
      this[field] = data[field];
    }

    this.errors = new Errors();
  }

  data() {
    let data = {};

    for (let field in this.originalData) {
      data[field] = this[field];
    }

    return data;
  }

  updateData(data) {
    for (let field in this.originalData) {
      this[field] = data[field];
    }
  }

  post(url) {
    return this.submit("post", url);
  }

  submit(requestType, url) {
    return new Promise((resolve, reject) => {
      axios[requestType](url, this.data())
        .then(({ data }) => {
          resolve(data);
        })
        .catch((error) => {
          this.errors.record(error.response.data.errors);
          reject(this.errors);
        });
    });
  }
}

export default Form;
