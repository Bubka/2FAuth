<template>
    <div v-if="active" class="loader-wrapper">
        <div class="spinner">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
        <p>{{ message }}</p>
    </div>
</template>

<script>
export default {
    name: 'Loader',
    props: {
        active: Boolean,
        message: String
    }
}
</script>

<style scoped>
.loader-wrapper {
    text-align: center;
    z-index: 10000;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(50,50,50,0.68);
}
.loader-wrapper p {
    font-size: 1.2em;
    font-weight: 300;
    margin-top: 5px;
    letter-spacing: 1px;
    color: rgb(255, 255, 255);
}
.spinner {
    display: inline-block;
    position: relative;
    width: 80px;
    height: 80px;
}

.spinner div {
    box-sizing: border-box;
    display: block;
    position: absolute;
    width: 64px;
    height: 64px;
    margin: 8px;
    border: 8px solid #6916a0;
    border-radius: 50%;
    animation: spinner 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
    border-color: #6916a0 transparent transparent transparent;
}
.spinner div:nth-child(1) {
    animation-delay: -0.45s;
}
.spinner div:nth-child(2) {
    animation-delay: -0.3s;
}
.spinner div:nth-child(3) {
    animation-delay: -0.15s;
}
@keyframes spinner {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
