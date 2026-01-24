<script setup>
</script>

<template>
  <div class="parent-loader">
    <div class="logo-container p-2 shadow">
      <span class="spinner"></span>
      <span class="background"></span>
      <img class="logo" src="../../../public/favicon.ico" height="28" />
    </div>
  </div>
</template>

<style scoped>
.logo-container {
  /* align children in the center */
  display: flex;
  background-color: white;

  align-items: center;
  justify-content: center;
  position: relative;
  z-index: 999999999;

  /* a circle with 60x60 pixels */
  width: 70px;
  height: 70px;
  border-radius: 50%;
}
.background {
  border-radius: 50%;
  background-image: linear-gradient(0deg, #0f1013, #252730);
  box-shadow: 0 4px 4px -1px rgba(0, 0, 0, 0.6),
    0 4px 6px 1px rgba(0, 0, 0, 0.3), 0 1px 2px 1px rgba(0, 0, 0, 0) inset,
    0 18px 32px -2px rgba(255, 255, 255, 0.1) inset;
}
.logo {
  z-index: 2;
}
.spinner {
  border: 4px solid white;
  height: 100%;
  position: absolute;
  display: inline-block;
  width: 100%;
  border-radius: 50%;
  border-top: 4px solid #073c4d;

  /* glowing with shadow (30% of #ae34db) */
  /* box-shadow: 0 -5px 5px #0a6480; */

  /* add spin animation */
  animation: spin 2s linear infinite;
}
@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.parent-loader {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
  opacity: 5;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 9999;
  background-color: rgba(249, 249, 249, 0.29);
}
.sbl-seven-circles {
  height: 9rem;
  width: 9rem;
  color: #5a5a5a;
  position: relative;
  animation: rotate 1s linear infinite;
  display: inline-block;
}
.sbl-seven-circles div {
  height: 50%;
  width: 50%;
  border-radius: 50%;
  border: 1px solid;
  position: absolute;
}
.sbl-seven-circles div:nth-child(1) {
  top: 10%;
  left: 5%;
}
.sbl-seven-circles div:nth-child(2) {
  top: 0;
  left: 25%;
}
.sbl-seven-circles div:nth-child(3) {
  top: 10%;
  right: 5%;
}
.sbl-seven-circles div:nth-child(4) {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  margin: auto;
}
.sbl-seven-circles div:nth-child(5) {
  bottom: 0;
  left: 25%;
}
.sbl-seven-circles div:nth-child(6) {
  bottom: 10%;
  left: 5%;
}
.sbl-seven-circles div:nth-child(7) {
  bottom: 10%;
  right: 5%;
}

@keyframes rotate {
  0% {
    transform: rotate(0);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
