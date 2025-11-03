<script setup>
    import { UseColorMode } from '@vueuse/components'
    import { useUserStore } from '@/stores/user'
    import { LucideCodeXml, LucideFlaskConical, LucideFolderGit2, LucideGraduationCap } from 'lucide-vue-next'

    const $2fauth = inject('2fauth')
    const user = useUserStore()
    const router = useRouter()
    
    const returnTo = router.options.routes.find((route) => route.path == router.options.history.state.back).name;

</script>

<template>
    <ResponsiveWidthWrapper>
    <UseColorMode v-slot="{ mode }">
        <h1 class="title has-text-grey-dark">{{ $t('heading.about') }}</h1>
        <p class="block">
            <span :class="mode == 'dark' ? 'has-text-white':'has-text-black'">
                <span class="is-size-5">2FAuth</span>
                <span v-if="user.isAuthenticated"> v{{ $2fauth.version }}</span>
            </span>
            <br />
            {{ $t('message.2fauth_teaser')}}
        </p>
        <img class="about-logo" src="logo.svg" alt="2FAuth logo" />
        <p class="block">
            ©Bubka <a class="is-size-7" href="https://github.com/Bubka/2FAuth/blob/master/LICENSE">AGPL-3.0 license</a>
        </p>
        <h2 class="title is-5 has-text-grey-light">
            {{ $t('heading.resources') }}
        </h2>
        <div class="buttons">
            <a class="button" :class="{'is-dark' : mode == 'dark'}" href="https://github.com/Bubka/2FAuth" target="_blank">
                <span class="icon is-small">
                    <LucideFolderGit2 />
                </span>
                <span>Github</span>
            </a>
            <a class="button" :class="{'is-dark' : mode == 'dark'}" href="https://docs.2fauth.app/" target="_blank">
                <span class="icon is-small">
                    <LucideGraduationCap />
                </span>
                <span>{{ $t('label.docs') }}</span>
            </a>
            <a class="button" :class="{'is-dark' : mode == 'dark'}" href="https://demo.2fauth.app/" target="_blank">
                <span class="icon is-small">
                    <LucideFlaskConical />
                </span>
                <span>{{ $t('label.demo') }}</span>
            </a>
            <a class="button" :class="{'is-dark' : mode == 'dark'}" href="https://docs.2fauth.app/resources/rapidoc.html" target="_blank">
                <span class="icon is-small">
                    <LucideCodeXml />
                </span>
                <span>{{ $t('label.api') }}</span>
            </a>
        </div>
        <h2 class="title is-5 has-text-grey-light">
            {{ $t('heading.credits') }}
        </h2>
        <ul>
            <li>{{ $t('message.made_with') }}&nbsp;<a href="https://docs.2fauth.app/credits/">Laravel, Bulma CSS, Vue.js and more</a></li>
            <li>{{ $t('message.ui_icons_by') }}&nbsp;<a href="https://lucide.dev/">Lucide</a>&nbsp;<a class="is-size-7" href="https://lucide.dev/license">(ISC License)</a></li>
            <li>{{ $t('message.logos_by') }}&nbsp;<a href="https://2fa.directory/">2FA Directory</a>&nbsp;<a class="is-size-7" href="https://github.com/2factorauth/twofactorauth/blob/master/LICENSE.md">(MIT License)</a></li>
        </ul>
        <!-- footer -->
        <VueFooter>
            <template #default>
                <NavigationButton action="back" @goback="router.push({ name: returnTo })" :previous-page-title="$t('title.' + returnTo)" />
            </template>
        </VueFooter>
    </UseColorMode>
    </ResponsiveWidthWrapper>
</template>