<template>
    <div>
    </div>
</template>

<script>
    export default {
        name: 'TotpLooper',

        data() {
            return {
                generatedAt: null,
                remainingTimeout: null,
                initialStepToNextStepTimeout: null,
                stepToStepInterval: null,
                stepIndex: null,
            }
        },

        props: {
            step_count: {
                type: Number,
                default: 10
            },
            period : Number,
            generated_at: Number,
            autostart: {
                type: Boolean,
                default: true
            },
        },

        computed: {

            //                              |<----period p----->|
            //     |                        |                   |
            //     |------- ··· ------------|--------|----------|---------->
            //     |                        |        |          |
            //  unix T0                 Tp.start   Tgen_at    Tp.end
            //                              |        |          |
            //  elapsedTimeInCurrentPeriod--|<------>|          |
            //  (in ms)                     |        |          |
            //                              ● ● ● ● ●|● ◌ ◌ ◌ ◌ |
            //                              | |      ||         |
            //                              | |      |<-------->|--remainingTimeBeforeEndOfPeriod (for remainingTimeout)
            //    durationBetweenTwoSteps-->|-|<     ||         
            //   (for stepToStepInterval)   | |     >||<---durationFromInitialToNextStep (for initialStepToNextStepTimeout)
            //                                        |
            //                                        |
            //                                    stepIndex

            elapsedTimeInCurrentPeriod() {
                return this.generatedAt % this.period
            },

            remainingTimeBeforeEndOfPeriod() {
                return this.period - this.elapsedTimeInCurrentPeriod
            },

            durationBetweenTwoSteps() {
                return this.period / this.step_count
            },

            initialStepIndex() {
                let relativePosition = (this.elapsedTimeInCurrentPeriod * this.step_count) / this.period

                return (Math.floor(relativePosition) + 0)
            },
        },

        mounted: function() {
            if (this.autostart == true) {
                this.startLoop()
            }
        },

        methods: {

            startLoop: function() {

                this.clearLooper()
                this.generatedAt = this.generated_at

                this.$emit('loop-started', this.initialStepIndex)

                this.stepIndex = this.initialStepIndex
                let self = this;

                // Main timeout that run until the end of the period
                this.remainingTimeout = setTimeout(function() {
                    self.clearLooper()
                    self.$emit('loop-ended')
                }, this.remainingTimeBeforeEndOfPeriod*1000);

                // During the remainingTimeout countdown we have to emit an event every durationBetweenTwoSteps seconds
                // except for the first next dot
                let durationFromInitialToNextStep =  (Math.ceil(this.elapsedTimeInCurrentPeriod / this.durationBetweenTwoSteps) * this.durationBetweenTwoSteps) - this.elapsedTimeInCurrentPeriod

                this.initialStepToNextStepTimeout = setTimeout(function() {
                    if( durationFromInitialToNextStep > 0 ) {
                        // self.activateNextStep()
                        self.stepIndex += 1
                        self.$emit('stepped-up', self.stepIndex)
                    }
                    self.stepToStepInterval = setInterval(function() {
                        // self.activateNextStep()
                        self.stepIndex += 1
                        self.$emit('stepped-up', self.stepIndex)
                    }, self.durationBetweenTwoSteps*1000)
                }, durationFromInitialToNextStep*1000)
            },

            clearLooper: function() {
                clearTimeout(this.remainingTimeout)
                clearTimeout(this.initialStepToNextStepTimeout)
                clearInterval(this.stepToStepInterval)
                this.stepIndex = this.generatedAt = null
            },

        },

        beforeDestroy () {
            this.clearLooper()
        },
    }
</script>