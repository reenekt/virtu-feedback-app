<template>
  <div>
    <table class="table">
      <thead class="table">
        <tr>
          <th>ID</th>
          <th>Имя</th>
          <th>Телефон</th>
          <th>Прикрепленный файл</th>
          <th>Дата создания</th>
          <th>Дата изменения</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="item in items"
          :key="'row-' + item.id"
        >
          <td>{{ item.id }}</td>
          <td>{{ item.fullname }}</td>
          <td>{{ item.contact_phone }}</td>
          <td>
            <a
              :href="apiUrl + '/feedback/' + item.id + '/attachment'"
              target="_blank"
            >
              {{ item.attachment_file }}
            </a>
            <a
              class="btn btn-outline-primary"
              :href="apiUrl + '/feedback/' + item.id + '/attachment?download=1'"
              target="_blank"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                class="bi bi-download"
                viewBox="0 0 16 16"
              >
                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
              </svg>
              Скачать
            </a>
          </td>
          <td>{{ item.created_at }}</td>
          <td>{{ item.updated_at }}</td>
        </tr>
      </tbody>
    </table>

    <div class="d-flex align-items-center">
      <nav aria-label="feedback table pagination">
        <ul class="pagination mb-0">
          <li class="page-item" :class="{ disabled: prevPage === null }">
            <a class="page-link" href="#" aria-label="Previous" @click="changePage(prevPage)">
              <span aria-hidden="true">&laquo;</span>
            </a>
          </li>
          <li
            v-for="page in paginationMeta.last_page"
            :key="'page-' + page"
            class="page-item"
            :class="{
              active: paginationMeta.current_page === page
            }"
            aria-current="page"
          >
            <a class="page-link" href="#" @click="changePage(page)">{{ page }}</a>
          </li>
          <li class="page-item" :class="{ disabled: nextPage === null }">
            <a class="page-link" href="#" aria-label="Next" @click="changePage(nextPage)">
              <span aria-hidden="true">&raquo;</span>
            </a>
          </li>
        </ul>
      </nav>

      <div v-if="loading" class="d-flex align-items-center ml-3">
        <div class="spinner-grow text-primary" role="status" />
        <strong class="ml-2">Загрузка...</strong>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue'

export default Vue.extend({
  name: 'FeedbackDataTable',
  props: {
    items: {
      required: true,
      type: Array,
    },
    paginationMeta: {
      required: true,
      type: Object,
      validator(value) {
        return typeof value === 'object' &&
          value !== null &&
          (('current_page' in value &&
          'last_page' in value &&
          'per_page' in value &&
          'from' in value &&
          'to' in value &&
          'total' in value) ||
          Object.keys(value).length === 0)
      },
    },
    loading: Boolean,
  },
  computed: {
    prevPage() {
      if (this.paginationMeta.current_page === 1) {
        return null
      }

      return this.paginationMeta.current_page - 1
    },
    nextPage() {
      if (this.paginationMeta.current_page === this.paginationMeta.last_page) {
        return null
      }

      return this.paginationMeta.current_page + 1
    },
    apiUrl() {
      return this.$config.env.apiUrl
    },
  },
  methods: {
    changePage(page = null) {
      if (!page) {
        return
      }
      this.$emit('change:page', page)
    },
  },
})
</script>

<style scoped>

</style>
