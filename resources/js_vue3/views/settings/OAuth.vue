<script setup>
    import Form from '@/components/formElements/Form'
    import userService from '@/services/userService'
    import SettingTabs from '@/layouts/SettingTabs.vue'
    import { useNotifyStore } from '@/stores/notify'
    import { UseColorMode } from '@vueuse/components'
    import Spinner from '@/components/Spinner.vue'

    const $2fauth = inject('2fauth')
    const notify = useNotifyStore()
    const returnTo = useStorage($2fauth.prefix + 'returnTo', 'accounts')
    const { copy } = useClipboard({ legacy: true })

    const tokens = ref([])
    const isFetching = ref(false)
    const isRemoteUser = ref(false)
    const createPATModalIsVisible = ref(false)
    const accessToken = ref(null)
    const token_id = ref(null)

    onMounted(() => {
        fetchTokens()
    })

    const form = reactive(new Form({
        name : '',
    }))

    /**
     * Get all groups from backend
     */
    function fetchTokens() {
        isFetching.value = true

        userService.getPersonalAccessTokens({returnError: true})
        .then(response => {
            response.data.forEach((data) => {
                if (data.id === token_id.value) {
                    data.value = accessToken.value
                    tokens.value.unshift(data)
                }
                else {
                    tokens.value.push(data)
                }
            })
        })
        .catch(error => {
            if( error.response.status === 400 ) {
                // The backend returns a 400 response for routes with the
                // rejectIfReverseProxy middleware
                isRemoteUser.value = true
            }
            else {
                notify.error(error)
            }
        })
        .finally(() => {
            isFetching.value = false
            token_id.value = null
            accessToken.value = null
        })
    }


    /**
     * Open the PAT creation view
     */
    function showPATcreationForm() {
        if (isRemoteUser.value) {
            notify.warn({ text: trans('errors.unsupported_with_reverseproxy') })
        }
        else createPATModalIsVisible.value = true
    }
    
    /**
     * Generate a fresh token
     */
    function generatePAToken() {
        form.post('/oauth/personal-access-tokens')
        .then(response => {
            accessToken.value = response.data.accessToken
            token_id.value = response.data.token.id
            fetchTokens()
            createPATModalIsVisible.value = false
        })
    }

    /**
     * revoke a token (after confirmation)
     */
    function revokeToken(tokenId) {
        if(confirm(trans('settings.confirm.revoke'))) {
            userService.deletePersonalAccessToken(tokenId)
            .then(response => {
                // Remove the revoked token from the collection
                tokens.value = tokens.value.filter(a => a.id !== tokenId)
                notify.success({ text: trans('settings.token_revoked') })
            })
        }
    }

    function copyToClipboard(data) {
        copy(data)
        notify.success({ text: trans('commons.copied_to_clipboard') })
    }

    onBeforeRouteLeave((to) => {
        if (! to.name.startsWith('settings.')) {
            notify.clear()
        }
    })
</script>

<template>
    <div>
        <SettingTabs :activeTab="'settings.oauth.tokens'" />
        <div class="options-tabs">
            <FormWrapper>
                <div v-if="isRemoteUser" class="notification is-warning has-text-centered" v-html="$t('auth.auth_handled_by_proxy')" />
                <h4 class="title is-4 has-text-grey-light">{{ $t('settings.personal_access_tokens') }}</h4>
                <div class="is-size-7-mobile">
                    {{ $t('settings.token_legend')}}
                </div>
                <div class="mt-3">
                    <a tabindex="0" class="is-link" @click="showPATcreationForm" @keyup.enter="showPATcreationForm">
                        <FontAwesomeIcon :icon="['fas', 'plus-circle']" /> {{ $t('settings.generate_new_token')}}
                    </a>
                </div>
                <div v-if="tokens.length > 0">
                    <div v-for="token in tokens" :key="token.id" class="group-item is-size-5 is-size-6-mobile">
                        <FontAwesomeIcon v-if="token.value" class="has-text-success" :icon="['fas', 'check']" /> {{ token.name }}
                        <!-- revoke link -->
                        <div class="tags is-pulled-right">
                            <UseColorMode v-slot="{ mode }">
                                <button v-if="token.value" class="button tag" :class="{'is-link': mode != 'dark'}" @click.stop="copyToClipboard(token.value)">
                                    {{ $t('commons.copy') }}
                                </button>
                                <button class="button tag" :class="mode === 'dark' ? 'is-dark':'is-white'" @click="revokeToken(token.id)" :title="$t('settings.revoke')">
                                    {{ $t('settings.revoke') }}
                                </button>
                            </UseColorMode>
                        </div>
                        <!-- warning msg -->
                        <span v-if="token.value" class="is-size-7-mobile is-size-6 my-3">
                            {{ $t('settings.make_sure_copy_token') }}
                        </span>
                        <!-- token value -->
                        <span v-if="token.value" class="pat is-family-monospace is-size-6 is-size-7-mobile has-text-success">
                            {{ token.value }}
                        </span>
                    </div>
                    <div class="mt-2 is-size-7 is-pulled-right">
                        {{ $t('settings.revoking_a_token_is_permanent')}}
                    </div>
                </div>
                <Spinner :isVisible="isFetching && tokens.length === 0" />
                <!-- footer -->
                <VueFooter :showButtons="true">
                    <!-- close button -->
                    <p class="control">
                        <UseColorMode v-slot="{ mode }">
                            <RouterLink
                                id="btnClose"
                                :to="{ name: returnTo }"
                                class="button is-rounded"
                                :class="{'is-dark' : mode === 'dark'}"
                                tabindex="0"
                                role="button"
                                :aria-label="$t('commons.close_the_x_page', {pagetitle: $route.meta.title})">
                                {{ $t('commons.close') }}
                            </RouterLink>
                        </UseColorMode>
                    </p>
                </VueFooter>
            </FormWrapper>
        </div>
        <div v-if="createPATModalIsVisible" class="is-overlay has-background-black-ter modal-otp">
            <main class="main-section">
                <FormWrapper title="settings.forms.new_token">
                    <form @submit.prevent="generatePAToken" @keydown="form.onKeydown($event)">
                        <FormField v-model="form.name" fieldName="name" :fieldError="form.errors.get('name')" inputType="text" label="commons.name" autofocus />
                        <div class="field is-grouped">
                            <div class="control">
                                <VueButton :id="'btnGenerateToken'" :isLoading="form.isBusy" >
                                    {{ $t('commons.generate') }}
                                </VueButton>
                            </div>
                            <div class="control">
                                <VueButton @click="createPATModalIsVisible = false" :nativeType="'button'" id="btnCancel" :color="'is-text'">
                                    {{ $t('commons.cancel') }}
                                </VueButton>
                            </div>
                        </div>
                    </form>
                </FormWrapper>
            </main>
        </div>
    </div>
</template>
