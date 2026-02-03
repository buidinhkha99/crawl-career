<template>
  <div>
    <heading class="mb-6">{{__('Change Password')}}</heading>

    <div class="card">
      <div class="flex border-40">
        <div class="w-1/5 px-8 py-6">
          <label for="old_password" class="inline-block text-80 pt-2 leading-tight">
            {{ __('Current password') }}
            <span class="text-required text-danger text-sm">*</span>
          </label>
        </div>
        <div class="py-6 px-8 w-1/2">
          <input
              v-model="current_password"
              id="old_password" type="password"
              class="w-full form-control form-input form-input-bordered"
              :class="{errorInput: errors['current_pw']}"
              name="current_password">
          <p v-if="errors['current_pw']" style="padding-top: 10px; color: #ff1515">{{ errors['current_pw'] }}</p>
        </div>
      </div>

      <div class="flex border-40">
        <div class="w-1/5 px-8 py-6">
          <label for="new_password" class="inline-block text-80 pt-2 leading-tight">
            {{ __('New password') }}
            <span class="text-required text-danger text-sm">*</span>
          </label>
        </div>
        <div class="py-6 px-8 w-1/2">
          <input
              v-model="new_password"
              id="new_password" type="password"
              class="w-full form-control form-input form-input-bordered"
              :class="{errorInput: errors['new_pw']}"
              name="new_password">
          <p v-if="errors['new_pw']" style="padding-top: 10px; color: #ff1515">{{ errors['new_pw'] }}</p>
          <password-meter :password="new_password" />
        </div>
      </div>

      <div class="flex border-40">
        <div class="w-1/5 px-8 py-6">
          <label for="confirm_password" class="inline-block text-80 pt-2 leading-tight">
            {{ __('Confirm password') }}
            <span class="text-required text-danger text-sm">*</span>
          </label>
        </div>
        <div class="py-6 px-8 w-1/2">
          <input
              v-model="confirm_new_password"
              id="confirm_password" type="password"
              class="w-full form-control form-input form-input-bordered"
              :class="{errorInput: errors['confirm_new_pw']}"
              name="confirm_new_password"
          >
          <p v-if="errors['confirm_new_pw']" style="padding-top: 10px; color: #ff1515">{{ errors['confirm_new_pw'] }}</p>
          <password-meter :password="confirm_new_password" />
        </div>
      </div>

      <div class="flex justify-end">
        <div class="customize-btn">
          <button @click="submitForm()" type="submit">
            {{ __('Save Password') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import passwordMeter from "vue-simple-password-meter";
export default {
  components: { passwordMeter },
  data() {
    return {
      errors: [],
      current_password: null,
      new_password: null,
      confirm_new_password: null,
      min_password_size: null
    }
  },
  mounted() {
    this.getPasswordSize()
  },
  methods: {
    getPasswordSize: function () {
      Nova.request().get('/nova-vendor/reset-password/min-password-size').then(response => {
            this.min_password_size = response.data.minpassw;
          }
      );
    },
    checkForm: function () {
      this.errors = [];
      if (!this.current_password) {
        this.errors['current_pw'] = "Current password is required"
      } else {
        delete this.errors['current_pw'];
      }

      if (!this.new_password) {
        this.errors['new_pw'] = "New password is required";
      }  else if (this.new_password && this.new_password.length < this.min_password_size) {
        this.errors['new_pw'] = "New password must be at least " + this.min_password_size;
      } else {
        delete this.errors['new_pw'];
      }

      if (!this.confirm_new_password) {
        this.errors['confirm_new_pw'] = 'Confirm password is required';
      } else if (this.confirm_new_password && this.confirm_new_password.length < this.min_password_size) {
        this.errors['confirm_new_pw'] = "Confirm password must be at least " + this.min_password_size;
      } else if (this.confirm_new_password !== this.new_password) {
        this.errors['confirm_new_pw'] = "Confirm Password does not match";
      } else {
        delete this.errors['confirm_new_pw'];
      }
    },
    submitForm: function () {
      this.checkForm();
      if (Object.keys(this.errors).length > 0)
        return;

      let params = new FormData();
      params.append('current_password', this.current_password)
      params.append('new_password', this.new_password)
      params.append('confirm_new_password', this.confirm_new_password)

      Nova.request().post('/nova-vendor/reset-password/', params)
          .then(() => {
        Nova.success('Change password successfully');
      }).catch(e => {
        const errors = e.response.data.errors;
        if ('current_password' in errors) {
          this.errors['current_pw'] = errors['current_password'][0];
        }
        if ('new_password' in errors) {
          this.errors['new_pw'] = errors['new_password'][0];
        }
        if ('confirm_new_password' in errors) {
          this.errors['confirm_new_pw'] = errors['confirm_new_password'][0];
        }
        Nova.error('These was a problem when submitting form');
      })
    }
  }
}
</script>

<style>
/* Scoped Styles */
</style>
