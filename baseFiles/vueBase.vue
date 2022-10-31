<script>
  //In script setup method this won't work. So need to do it this way
  import * as iconPath from './iconPaths'

  export default {
    components: {
      ...iconPath
    },
  }
</script>
<template>
    <svg 
      xmlns="http://www.w3.org/2000/svg" 
      :width="props.width" 
      :height="props.height" 
      viewBox="0 0 24 24" 
      :aria-labelledby="props.name" 
      role="presentation"
    >
      <title :id="props.name" lang="en">{{props.name}} icon</title>
      <g>
        <component :is="props.name+'_'+props.type"></component>
      </g>
    </svg>
</template>
<script setup>
  import { ref } from 'vue'

  const props = defineProps({
      name: {
          type: String,
          default: 'box'
      },
      width: {
          type: [Number, String],
          default: 18
      },
      height: {
          type: [Number, String],
          default: 18
      },
      color: {
        type: String,
        default: 'currentColor'
      },
  })

</script>
