<template>
    <div v-if="active" class="spinner-container">
        <div class="spinner-wrapper">
            <div class="spinner">
                <div class="rect-1"></div>
                <div class="rect-2"></div>
                <div class="rect-3"></div>
                <div class="rect-4"></div>
                <div class="rect-5"></div>
            </div>
            <span>{{ message }}</span>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Spinner',
    props: {
        active: {
            type: Boolean,
            default: false
        },
        message: String,
    }
}
</script>

<style scoped>
.spinner-container {
    text-align: center;
    z-index: 10000;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.spinner-wrapper p {
    font-size: 1.2em;
    font-weight: 300;
    margin-top: 5px;
    letter-spacing: 1px;
}
.spinner {
    margin: auto;
    width: 50px;
    height: 40px;
    text-align: center;
    font-size: 10px;
}
.spinner > div {
    height: 100%;
    width: 6px;
    display: inline-block;
    --webkit-animation: stretch-delay 1.2s infinite ease-in-out;
    animation: stretch-delay 1.2s infinite ease-in-out;
}
.spinner .rect-2 {
    --webkit-animation-delay: -1.1s;
    animation-delay: -1.1s;
}
.spinner .rect-3 {
    --webkit-animation-delay: -1.0s;
    animation-delay: -1.0s;
}
.spinner .rect-4 {
    --webkit-animation-delay: -0.9s;
    animation-delay: -0.9s;
}
.spinner .rect-5 {
    --webkit-animation-delay: -0.8s;
    animation-delay: -0.8s;
}
@-webkit-keyframes stretch-delay {
    0%, 40%, 100% {
        --webkit-transform: scaleY(0.4)
    }
    20% {
        --webkit-transform: scaleY(1.0)
    }
}
@keyframes stretch-delay {
    0%, 40%, 100% {
        transform: scaleY(0.4);
        --webkit-transform: scaleY(0.4)
    }
    20% {
        transform: scaleY(1.0);
        --webkit-transform: scaleY(1.0)
    }
}
</style>
