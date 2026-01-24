<script>
export default {
    props: ['links'],
    data() {
        return {};
    },
};
</script>
<template>   

<!-- Breadcrumb -->
<!-- <div class="breadcumbs">
    <p v-for="(link,index) in links" class="bg-white" :key="index">
      <RouterLink class="text-appgray font-bold" :to="link.url">{{ link.text }}</RouterLink>
      <span v-if="links.length - 1 != index">/</span>
    </p>
  </div> -->
  <nav class="flex bg-white mb-4 items-center shadow-md text-[#2083A2] dark:bg-gray-800 dark:border-gray-700" style=""
    aria-label="Breadcrumb">
  <ol class="inline-flex items-center space-x-1 md:space-x-3">
    <li class="inline-flex items-center ml-2">
        <router-link style="text-decoration: none;" to="/"
          class="inline-flex mt-2 capitalize text-[#2083A2] items-center text-sm font-normal hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
        <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
        Home
      </router-link>
    </li>
    <li v-for="(link,index) in links" :key="index">
      <div  class="flex items-center mt-2">
        <svg class="w-3 h-3 " fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
          <router-link style="text-decoration: none;" :to="link.url"
            class="ml-1 text-sm capitalize font-normal text-[#2083A2] hover:text-gray-900 md:ml-2 dark:text-gray-400 dark:hover:text-white">{{
              link.text }}</router-link>
      </div>
    </li>
  
  </ol>
</nav>

</template>
<style scoped>

</style>
