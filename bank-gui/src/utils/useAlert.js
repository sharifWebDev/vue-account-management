import Swal from "sweetalert2";
import toastr from "toastr";
import "toastr/build/toastr.min.css";

// Toastr Default Configuration
toastr.options = {
  closeButton: true,
  progressBar: true,
  positionClass: "toast-top-right",
  timeOut: 5000, // Auto close after 5 seconds
};

// SweetAlert Confirm Delete
export const confirmDelete = async () => {
  const result = await Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  });

  return result.isConfirmed;
};

// Show SweetAlert Notification
export const showAlert = (type, title, message) => {
  Swal.fire({
    icon: type,
    title: title,
    text: message,
  });
};

// Toastr Notifications
export const info = (message) => toastr.info(message, "Info");
export const success = (message) => toastr.success(message, "Success");
export const warning = (message) => toastr.warning(message, "Warning");
export const error = (message) => toastr.error(message, "Error");
