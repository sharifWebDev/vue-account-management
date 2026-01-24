// src/utils/alerts.js
import toastr from "toastr";

// Info alert
export const info = (message, title = "Processing", timeout = 0) => {
  return toastr.info(message, title, {
    closeButton: true,
    timeOut: timeout,
    extendedTimeOut: 0,
    progressBar: true,
  });
};

// Success alert
export const success = (message, title = "Success", timeout = 5000) => {
  return toastr.success(message, title, {
    timeOut: timeout,
  });
};

// Error alert
export const error = (message, title = "Error", timeout = 5000) => {
  return toastr.error(message, title, {
    timeOut: timeout,
  });
};

// Warning alert
export const warning = (message, title = "Warning", timeout = 5000) => {
  return toastr.warning(message, title, {
    timeOut: timeout,
  });
};

export default { info, success, error, warning };
