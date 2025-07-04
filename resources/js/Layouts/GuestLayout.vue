<template>
  <div class="login-container">
    <div class="login-wrapper">
      <div class="login-content">
        <slot />
      </div>
    </div>
  </div>
</template>

<script setup>
// Component logic if needed
</script>

<style scoped>
/* Base styles for all devices */
.login-container {
  display: flex;
  min-height: 100vh;
  align-items: center;
  justify-content: center;
  background: linear-gradient(#ffffff, #e0e0e0da);
  background-size: 100% 100%;
}

.login-wrapper {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 470px;
  background: transparent;
}

.login-content {
  background: white;
  padding: 3.5rem 1.5rem;
  border-radius: 0.75rem;
  width: 100%;
  max-width: 28rem;
  border: 1px solid #f3f4f6; /* Default border color */
}

/* Styles for screens 768px and up */
@media (min-width: 768px) {
  .login-content {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
  }
}

/* Full width styles only below 480px */
@media (max-width: 480px) {
  .login-container {
    background: white;
    align-items: flex-start;
    padding-top: 0;
  }

  .login-wrapper {
    height: 100vh;
    min-height: auto;
    padding: 0;
    box-shadow: none;
  }

  .login-content {
    border: 1px solid #9ca3af;
    padding: 2rem 1rem;
    border-radius: 0;
    max-width: 100%;
    height: 100%;
    width: 100%;
  }
}

/* Styles for 481px to 767px - maintains card layout */
@media (min-width: 481px) and (max-width: 767px) {
  .login-content {
    margin: 2rem;
    border: 1px solid #e5e7eb;
  }
}
</style>
