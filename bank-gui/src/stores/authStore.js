import { defineStore } from "pinia";
import axiosClient from "@/axiosClient";
import router from "@/router";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    token: localStorage.getItem("authToken") || null,
    user: JSON.parse(localStorage.getItem("user")) || null,
    isLoading: false,
    error: null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,

    fullName: (state) => state.user?.name || "",

    initials: (state) => {
      if (!state.user?.name) return "U";
      return state.user.name
        .split(" ")
        .map((n) => n.charAt(0))
        .join("")
        .toUpperCase();
    },

    role: (state) => state.user?.user_type || state.user?.role || "user",
  },

  actions: {
    /* ===============================
       TOKEN & USER
    =============================== */
    setToken(token) {
      this.token = token;
      localStorage.setItem("authToken", token);
      axiosClient.defaults.headers.common["Authorization"] = `Bearer ${token}`;
    },

    setUser(user) {
      this.user = user;
      localStorage.setItem("user", JSON.stringify(user));
    },

    /* ===============================
       LOGIN
    =============================== */
    async login(credentials) {
      this.isLoading = true;
      this.error = null;

      try {
        const response = await axiosClient.post("/auth/login", credentials);

        const { user, token } = response.data.data;

        this.setToken(token);
        this.setUser(user);

        router.push("/");

        return { success: true, data: { user, token } };
      } catch (error) {
        this.error =
          error.response?.data?.message ||
          "Login failed. Please check your credentials.";

        return {
          success: false,
          error: this.error,
          status: error.response?.status,
        };
      } finally {
        this.isLoading = false;
      }
    },

    /* ===============================
       REGISTER
    =============================== */
    async register(payload) {
      this.isLoading = true;
      this.error = null;

      try {
        const response = await axiosClient.post("/auth/register", payload);

        return { success: true, data: response.data };
      } catch (error) {
        this.error = error.response?.data?.message || "Registration failed.";

        return {
          success: false,
          error: this.error,
          errors: error.response?.data?.errors || {},
        };
      } finally {
        this.isLoading = false;
      }
    },

    /* ===============================
       CHANGE PASSWORD
    =============================== */
    async changePassword(payload) {
      this.isLoading = true;
      this.error = null;

      try {
        await axiosClient.post("/auth/change-password", payload);
        return { success: true };
      } catch (error) {
        this.error = error.response?.data?.message || "Password change failed.";
        return { success: false, error: this.error };
      } finally {
        this.isLoading = false;
      }
    },

    /* ===============================
       INIT AUTH (PAGE REFRESH SAFE)
    =============================== */
    initializeAuth() {
      const token = localStorage.getItem("authToken");
      const user = localStorage.getItem("user");

      if (token && user) {
        this.token = token;
        this.user = JSON.parse(user);
        axiosClient.defaults.headers.common["Authorization"] =
          `Bearer ${token}`;
        return true;
      }
      return false;
    },

    /* ===============================
       LOGOUT
    =============================== */
    logout() {
      this.token = null;
      this.user = null;

      localStorage.removeItem("authToken");
      localStorage.removeItem("user");

      delete axiosClient.defaults.headers.common["Authorization"];

      router.push("/login");
    },

    clearError() {
      this.error = null;
    },
  },
});
