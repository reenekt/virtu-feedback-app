<template>
  <div class="row my-3">
    <div class="col-6 mx-auto">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title text-center">
            Заказать обратный звонок
          </h4>

          <feedback-form />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import FeedbackForm from '../components/FeedbackForm'

export default {
  components: { FeedbackForm },
}
</script>
