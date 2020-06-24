<template>
    <div class="container">
        <div class="card card-default m-5">
            <div class="card-header">Dashboard</div>

            <div class="card-body">
                Hello, {{ $auth.user().username }}
            </div>
        </div>
    </div>
</template>

<script>

  export default {
    data() {
      return {
        //
      }
    },
    components: {
      //
    },
  }
</script>
