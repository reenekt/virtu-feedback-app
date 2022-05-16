<template>
  <div class="container mt-3">
    <div class="row">
      <div class="col">
        <div class="card w-100">
          <div class="card-body">
            <feedback-data-table
              :items="feedbackData.data"
              :pagination-meta="feedbackData.meta"
              :loading="$fetchState.pending"
              @change:page="onChangePage"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue'
import FeedbackDataTable from '../../components/FeedbackDataTable'

export default Vue.extend({
  name: 'FeedbackListPage',
  components: { FeedbackDataTable },
  layout: 'admin',
  data() {
    return {
      feedbackData: {
        data: [],
        meta: {},
      },
      currentPage: 1,
    }
  },
  async fetch() {
    await this.fetchFeedbackList(this.currentPage)
  },
  methods: {
    async fetchFeedbackList(page = 1) {
      const axiosConfig = {
        params: {
          page,
        },
      }

      this.feedbackData = await this.$axios.$get('/feedback', axiosConfig)
    },

    onChangePage(page) {
      this.currentPage = page
      this.$fetch()
    },
  },
})
</script>

<style scoped>

</style>
