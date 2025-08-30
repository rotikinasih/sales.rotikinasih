<template>
  <Head>
    <title>Login - Website Penjualan</title>
  </Head>
  <div class="login-bg">
    <div class="login-container">
      <div class="login-card">
        <div class="login-header">
          <div class="login-icon">
            <img src="/images/logo.png" alt="Logo" style="height: 60px;">
          </div>
          <h2 class="mb-1">Login</h2>
          <p class="mb-3 text-muted">Website</p>
        </div>
        <form @submit.prevent="submit">
          <div class="form-group mb-3">
            <label class="form-label">Username</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
              <input type="text" class="form-control" v-model="form.username" :class="{ 'is-invalid': errors.username }" placeholder="Username">
            </div>
            <div v-if="errors.username" class="invalid-feedback d-block">
              {{ errors.username }}
            </div>
          </div>
          <div class="form-group mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
              <input :type="showPassword ? 'text' : 'password'" class="form-control" v-model="form.password" :class="{ 'is-invalid': errors.password }" placeholder="Password">
              <button type="button" class="btn btn-outline-secondary" @click="toggleShow" tabindex="-1">
                <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
              </button>
            </div>
            <div v-if="errors.password" class="invalid-feedback d-block">
              {{ errors.password }}
            </div>
          </div>
          <button class="btn btn-primary w-100 shadow-sm py-2 mt-2" type="submit">
            <i class="fas fa-sign-in-alt me-2"></i> Login
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import LayoutAuth from '../../Layouts/Auth.vue';
import { onMounted, reactive, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link } from '@inertiajs/inertia-vue3';

export default {
  layout: LayoutAuth,
  components: { Head, Link },
  props: {
    errors: Object,
    session: Object
  },
  setup() {
    const showPassword = ref(false);
    const toggleShow = () => { showPassword.value = !showPassword.value; };
    const form = reactive({ username: '', password: '' });
    const submit = () => {
      Inertia.post('/login', {
        username: form.username,
        password: form.password,
      }, {
        onSuccess: () => {
          setTimeout(() => { location.reload(); }, 50);
        },
      });
    };
    return { form, toggleShow, showPassword, submit };
  }
}
</script>

<style scoped>
.login-bg {
  min-height: 100vh;
  background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
  display: flex;
  align-items: center;
  justify-content: center;
}
.login-container {
  width: 100%;
  max-width: 400px;
  margin: auto;
}
.login-card {
  background: #fff;
  border-radius: 18px;
  box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.12);
  padding: 2.5rem 2rem 2rem 2rem;
  position: relative;
  overflow: hidden;
}
.login-header {
  text-align: center;
  margin-bottom: 1.5rem;
}
.login-icon {
  background: linear-gradient(135deg, #6366f1 0%, #60a5fa 100%);
  color: #fff;
  width: 60px;
  height: 60px;
  border-radius: 50%;
  margin: 0 auto 1rem auto;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  box-shadow: 0 4px 16px rgba(99,102,241,0.15);
}
.form-label {
  font-weight: 500;
  margin-bottom: 0.25rem;
}
.input-group-text {
  background: #f1f5f9;
  border: none;
}
.form-control:focus {
  border-color: #6366f1;
  box-shadow: 0 0 0 0.15rem rgba(99,102,241,.15);
}
.btn-primary {
  background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
  border: none;
  font-weight: 600;
  letter-spacing: 0.5px;
}
.btn-outline-secondary {
  border: none;
  background: transparent;
  color: #6366f1;
}
.invalid-feedback {
  font-size: 0.95em;
}
@media (max-width: 500px) {
  .login-card { padding: 1.5rem 0.5rem; }
  .login-container { max-width: 98vw; }
}
</style>
