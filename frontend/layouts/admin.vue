<template>
  <div class="d-flex flex-row vh-100">
    <nav class="d-flex flex-column flex-shrink-0 p-3 bg-light h-100" style="width: 280px;">
      <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="32"
          height="32"
          fill="currentColor"
          class="bi bi-bar-chart"
          viewBox="0 0 16 16"
        >
          <path d="M4 11H2v3h2v-3zm5-4H7v7h2V7zm5-5v12h-2V2h2zm-2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1h-2zM6 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm-5 4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3z" />
        </svg>
        <span class="fs-4">Admin dashboard</span>
      </a>
      <hr>
      <ul class="nav nav-pills flex-column mb-auto">
        <li>
          <a href="#" class="nav-link link-dark">
            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2" /></svg>
            Dashboard
          </a>
        </li>
        <li>
          <a href="#" class="nav-link active">
            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#table" /></svg>
            Feedback list
          </a>
        </li>
      </ul>
      <hr>
      <div class="dropdown">
        <a id="dropdownUser2" href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
          <strong>mdo</strong>
        </a>
        <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
          <li><a class="dropdown-item" href="#">New project...</a></li>
          <li><a class="dropdown-item" href="#">Settings</a></li>
          <li><a class="dropdown-item" href="#">Profile</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="#">Sign out</a></li>
        </ul>
      </div>
    </nav>
    <main class="w-100">
      <div class="container-fluid">
        <Nuxt />
      </div>
    </main>
  </div>
</template>

<script>
export default {
  /* eslint-disable-next-line vue/component-definition-name-casing */
  name: 'admin',
}
</script>

<style scoped>

</style>
