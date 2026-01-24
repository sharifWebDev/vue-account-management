import axios from 'axios';
import { useAuthStore } from '@/stores/authStore';

// Load API URL from environment variables
const API_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost/quick_pos/public/api/v1/';

const axiosClient = axios.create({
  baseURL: API_URL,
  // withCredentials: true,  // Ensures cookies (for CSRF) are sent with the request
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
});

// Request Interceptor to attach Authorization token
axiosClient.interceptors.request.use(
  (config) => {
    const authStore = useAuthStore();
    if (authStore.token) {
      config.headers.Authorization = `Bearer ${authStore.token}`;
    }
    config.headers.Accept = 'application/json';
    return config;
  },
  (error) => Promise.reject(error)
);

// Response Interceptor to handle errors globally
axiosClient.interceptors.response.use(
  (response) => response,
  (error) => {
    const authStore = useAuthStore();

    if (error.response) {
      const status = error.response.status;

      if (status === 401) {
        console.error('Unauthorized! Redirecting to login...');
        authStore.logout();  // Log the user out on 401 error
      } else if (status === 403) {
        console.error('Forbidden! You don’t have permission.');
      } else if (status === 500) {
        console.error('Server error! Try again later.');
      }
    }

    return Promise.reject(error);
  }
);

export default axiosClient;
