<template>
    <div class="has-text-light">
        <!-- <span>period = {{ period }}</span><br />
        <span>Started_at = {{ generatedAt }}</span><br />
        <span>active step = {{ stepIndex }}/{{ step_count }}</span><br /><br />
        <span>elapsedTimeInCurrentPeriod = {{ elapsedTimeInCurrentPeriod }}</span><br />
        <span>remainingTimeBeforeEndOfPeriod = {{ remainingTimeBeforeEndOfPeriod }}</span><br />
        <span>durationBetweenTwoSteps = {{ durationBetweenTwoSteps }}</span><br />
        <hr /> -->
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
            step_count: Number,
            period : null,
            generated_at: Number,

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
            this.generatedAt = this.generated_at
            this.startLoop()
        },

        methods: {

            startLoop: function() {

                this.clearLooper()
                this.$emit('loop-started', this.initialStepIndex)

                this.stepIndex = this.initialStepIndex
                let self = this;

                // Main timeout that run until the end of the period
                this.remainingTimeout = setTimeout(function() {
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
                // if( this.isTimeBased(this.internal_otp_type) ) {
                    clearTimeout(this.remainingTimeout)
                    clearTimeout(this.initialStepToNextStepTimeout)
                    clearInterval(this.stepToStepInterval)
                    this.stepIndex = null
                // }
            },


            // activateNextStep: function() {
            //     if(this.lastActiveStep.nextSibling !== null) {
            //         this.lastActiveStep.removeAttribute('data-is-active')
            //         this.lastActiveStep.nextSibling.setAttribute('data-is-active', true)
            //         this.lastActiveStep = this.lastActiveStep.nextSibling
            //     }
            // },

        },

        beforeDestroy () {
            this.clearLooper()
        },
    }
</script>