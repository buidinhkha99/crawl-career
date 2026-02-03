<template>
  <Card class="flex flex-col" style=" background: transparent;margin-bottom: -128px !important; margin-top: 0px;">
       <div>
           <h1 class="font-normal text-xl md:text-xl"><span>{{card.title}}</span></h1>
       </div>
  </Card>
</template>

<script>
export default {
  props: [
    'card',

    // The following props are only available on resource detail cards...
    // 'resource',
    // 'resourceId',
    // 'resourceName',
  ],

  mounted() {
  },
}
</script>
