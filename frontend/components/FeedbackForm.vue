<template>
  <form @submit.prevent="onSubmit">
    <div class="mb-3">
      <label for="fullname" class="form-label">Имя</label>
      <input
        id="fullname"
        v-model="feedback.fullname"
        :class="{
          'is-invalid': getErrors('fullname') !== null
        }"
        type="text"
        class="form-control"
        aria-describedby="fullname-hint"
      >
      <small id="fullname-hint" class="form-text">Имя или ФИО</small>
      <div
        v-for="(error, errorIndex) in getErrors('fullname')"
        :key="'error-' + errorIndex"
        class="invalid-feedback"
        v-text="error"
      />
    </div>

    <div class="mb-3">
      <label for="contact_phone" class="form-label">Номер телефона</label>
      <input
        id="contact_phone"
        v-model="feedback.contact_phone"
        v-mask="'+7 (###) ###-##-##'"
        :class="{
          'is-invalid': getErrors('contact_phone') !== null
        }"
        type="text"
        class="form-control"
        aria-describedby="contact_phone-hint"
      >
      <small id="contact_phone-hint" class="form-text">+7 (XXX) XXX-XX-XX</small>
      <div
        v-for="(error, errorIndex) in getErrors('contact_phone')"
        :key="'error-' + errorIndex"
        class="invalid-feedback"
        v-text="error"
      />
    </div>

    <div class="align-items-center d-flex">
      <button type="submit" class="btn btn-primary" :disabled="loading">
        <span v-show="!loading">Отправить</span>

        <span v-show="loading">
          <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true" />
          <span>Отправка...</span>
        </span>
      </button>
    </div>

    <div ref="alertSuccess" class="alert alert-success mt-3 fade d-none" role="alert">
      Заявка принята!
    </div>
    <div ref="alertError" class="alert alert-danger mt-3 fade d-none" role="alert">
      Ошибка при отправки заявки
    </div>
  </form>
</template>

<script>
import Vue from 'vue'
import { validationMixin } from 'vuelidate'
import { required, minLength } from 'vuelidate/lib/validators'
import { mask } from 'vue-the-mask'

// +7########## format validator
const phoneValidator = value => typeof value === 'string' &&
  value.replaceAll(/[()\s-]/g, '').length === 12 &&
  value[0] === '+'

export default Vue.extend({
  name: 'FeedbackForm',
  directives: { mask },
  mixins: [validationMixin],
  validations: {
    feedback: {
      fullname: {
        required,
        minLength: minLength(2),
      },
      contact_phone: {
        required,
        phoneValidator,
      },
    },
  },
  data() {
    return {
      loading: false,
      feedback: {
        fullname: null,
        contact_phone: '+7 (',
      },
      apiErrors: {},
    }
  },
  methods: {
    showAlertSuccess(delay = 5000) {
      this.$refs.alertSuccess.classList.remove('d-none')
      this.$refs.alertSuccess.classList.add('show')
      setTimeout(() => {
        this.$refs.alertSuccess.classList.remove('show')
      }, delay)
      setTimeout(() => {
        this.$refs.alertSuccess.classList.add('d-none')
      }, delay + 150)
    },
    showAlertError(delay = 5000) {
      this.$refs.alertError.classList.remove('d-none')
      this.$refs.alertError.classList.add('show')
      setTimeout(() => {
        this.$refs.alertError.classList.remove('show')
      }, delay)
      setTimeout(() => {
        this.$refs.alertError.classList.add('d-none')
      }, delay + 150)
    },
    getErrors(fieldKey) {
      const errors = []

      if (fieldKey in this.apiErrors) {
        errors.push(this.apiErrors[fieldKey])
      }

      if (this.$v.feedback[fieldKey].$error) {
        if (this.$v.feedback[fieldKey].required === false) {
          errors.push('Заполните поле')
        }
        if (this.$v.feedback[fieldKey].minLength === false) {
          const minLength = this.$v.feedback[fieldKey].$params.minLength.min
          errors.push('Минимальная длина: ' + minLength)
        }
        if (this.$v.feedback[fieldKey].phoneValidator === false) {
          errors.push('Неправильный формат номера')
        }
      }

      if (errors.length) {
        return errors
      }

      return null
    },
    async onSubmit() {
      await this.saveFeedback()
    },
    async saveFeedback() {
      this.$v.$touch()
      if (this.$v.$invalid) {
        return
      }

      try {
        this.apiErrors = {}
        this.loading = true

        const data = {
          fullname: this.feedback.fullname,
          contact_phone: this.feedback.contact_phone.replaceAll(/[()\s-]/g, ''),
        }

        await this.$axios.$post('/feedback', data)

        this.showAlertSuccess()
      } catch (error) {
        this.showAlertError()

        const errors = error.response.data.errors
        if (errors) {
          this.apiErrors = errors
        }
      } finally {
        this.loading = false
      }
    },
  },
})
</script>

<style scoped>

</style>
