import{R as O,u as T,a as C,b as K,S as D,a2 as H,d as M,r as j,e as _,f as n,g as u,i as t,k as L,p as f,m as o,F as G,h as l,t as a,C as B,j as r,w as A,l as d,H as k}from"./app-BsP-5XS6.js";import{F as U}from"./Form-BkUnzt6c.js";import{w as P}from"./webauthnService-Cj6-OgRY.js";/*! 2FAuth version 5.2.0 - Copyright (c) 2024 Bubka - https://github.com/Bubka/2FAuth */const q={class:"field"},z={class:"nav-links"},J={key:0},Q={key:1,class:"mt-4"},X={key:2,class:"columns mt-4 is-variable is-1"},Y={class:"column is-narrow py-1"},Z={class:"column py-1"},x={key:0,id:"lnkSignWithOpenID",class:"button is-link is-outlined is-small ml-2",href:"/socialite/redirect/openid"},ee={key:1,id:"lnkSignWithGithub",class:"button is-link is-outlined is-small ml-2",href:"/socialite/redirect/github"},te=["innerHTML"],se=["innerHTML"],ie={class:"nav-links"},ae=["aria-label"],ne={key:0,class:"mt-4"},oe={key:1,class:"columns mt-4 is-variable is-1"},le={class:"column is-narrow py-1"},re={class:"column py-1"},ue={key:0,id:"lnkSignWithOpenID",class:"button is-link is-outlined is-small mr-2",href:"/socialite/redirect/openid"},de={key:1,id:"lnkSignWithGithub",class:"button is-link is-outlined is-small mr-2",href:"/socialite/redirect/github"},fe={__name:"Login",setup(ce){const c=O("2fauth"),S=T(),p=C(),h=K(),g=D(),v=p.preferences.useWebauthnOnly?!0:H(c.prefix+"showWebauthnForm",!1),i=M(new U({email:"",password:""})),F=j(!1);function b(){i.clear(),v.value=!v.value}function I(e){h.clear(),i.post("/user/login",{returnError:!0}).then(async s=>{await p.loginAs({id:s.data.id,name:s.data.name,email:s.data.email,oauth_provider:s.data.oauth_provider,preferences:s.data.preferences,isAdmin:s.data.is_admin}),S.push({name:"accounts"})}).catch(s=>{s.response.status===401?h.alert({text:k("auth.forms.authentication_failed"),duration:1e4}):s.response.status!==422&&h.error(s)})}function R(){h.clear(),i.clear(),F.value=!0,P.authenticate(i.email).then(async e=>{await p.loginAs({id:e.data.id,name:e.data.name,email:e.data.email,oauth_provider:e.data.oauth_provider,preferences:e.data.preferences,isAdmin:e.data.is_admin}),S.push({name:"accounts"})}).catch(e=>{"webauthn"in e?e.name=="is-warning"?h.warn({text:k(e.message)}):h.alert({text:k(e.message)}):e.response.status===401?h.alert({text:k("auth.forms.authentication_failed"),duration:1e4}):e.response.status==422?i.errors.set(i.extractErrors(e.response)):h.error(e)}).finally(()=>{F.value=!1})}return(e,s)=>{const $=_("FormField"),V=_("FormButtons"),y=_("RouterLink"),w=_("FontAwesomeIcon"),W=_("FormWrapper"),E=_("FormPasswordField"),N=_("VueFooter");return n(),u(G,null,[t(v)?(n(),L(W,{key:0,title:"auth.forms.webauthn_login",punchline:"auth.welcome_to_2fauth"},{default:f(()=>[l("div",q,a(e.$t("auth.webauthn.use_security_device_to_sign_in")),1),l("form",{id:"frmWebauthnLogin",onSubmit:B(R,["prevent"]),onKeydown:s[1]||(s[1]=m=>t(i).onKeydown(m))},[o($,{modelValue:t(i).email,"onUpdate:modelValue":s[0]||(s[0]=m=>t(i).email=m),fieldName:"email",fieldError:t(i).errors.get("email"),inputType:"email",label:"auth.forms.email",autofocus:""},null,8,["modelValue","fieldError"]),o(V,{isBusy:t(F),caption:"commons.continue",submitId:"btnContinue"},null,8,["isBusy"])],32),l("div",z,[l("p",null,[r(a(e.$t("auth.webauthn.lost_your_device"))+"  ",1),o(y,{id:"lnkRecoverAccount",to:{name:"webauthn.lost"},class:"is-link"},{default:f(()=>[r(a(e.$t("auth.webauthn.recover_your_account")),1)]),_:1},8,["to"])]),t(p).preferences.useWebauthnOnly?d("",!0):(n(),u("p",J,[r(a(e.$t("auth.sign_in_using"))+"  ",1),l("a",{id:"lnkSignWithLegacy",role:"button",class:"is-link",onKeyup:A(b,["enter"]),onClick:b,tabindex:"0"},a(e.$t("auth.login_and_password")),33)])),t(g).disableRegistration==!1?(n(),u("p",Q,[r(a(e.$t("auth.forms.dont_have_account_yet"))+"  ",1),o(y,{id:"lnkRegister",to:{name:"register"},class:"is-link"},{default:f(()=>[r(a(e.$t("auth.register")),1)]),_:1})])):d("",!0),t(g).enableSso&&Object.values(t(c).config.sso).includes(!0)?(n(),u("div",X,[l("div",Y,a(e.$t("auth.or_continue_with")),1),l("div",Z,[t(c).config.sso.openid?(n(),u("a",x,[r(" OpenID"),o(w,{class:"ml-2",icon:["fab","openid"]})])):d("",!0),t(c).config.sso.github?(n(),u("a",ee,[r(" Github"),o(w,{class:"ml-2",icon:["fab","github-alt"]})])):d("",!0)])])):d("",!0)])]),_:1})):(n(),L(W,{key:1,title:"auth.forms.login",punchline:"auth.welcome_to_2fauth"},{default:f(()=>[t(c).isDemoApp?(n(),u("div",{key:0,class:"notification is-info has-text-centered is-radiusless",innerHTML:e.$t("auth.forms.welcome_to_demo_app_use_those_credentials")},null,8,te)):d("",!0),t(c).isTestingApp?(n(),u("div",{key:1,class:"notification is-warning has-text-centered is-radiusless",innerHTML:e.$t("auth.forms.welcome_to_testing_app_use_those_credentials")},null,8,se)):d("",!0),l("form",{id:"frmLegacyLogin",onSubmit:B(I,["prevent"]),onKeydown:s[4]||(s[4]=m=>t(i).onKeydown(m))},[o($,{modelValue:t(i).email,"onUpdate:modelValue":s[2]||(s[2]=m=>t(i).email=m),fieldName:"email",fieldError:t(i).errors.get("email"),inputType:"email",label:"auth.forms.email",autofocus:""},null,8,["modelValue","fieldError"]),o(E,{modelValue:t(i).password,"onUpdate:modelValue":s[3]||(s[3]=m=>t(i).password=m),fieldName:"password",fieldError:t(i).errors.get("password"),label:"auth.forms.password"},null,8,["modelValue","fieldError"]),o(V,{isBusy:t(i).isBusy,caption:"auth.sign_in",submitId:"btnSignIn"},null,8,["isBusy"])],32),l("div",ie,[l("p",null,[r(a(e.$t("auth.forms.forgot_your_password"))+"  ",1),o(y,{id:"lnkResetPwd",to:{name:"password.request"},class:"is-link","aria-label":e.$t("auth.forms.reset_your_password")},{default:f(()=>[r(a(e.$t("auth.forms.request_password_reset")),1)]),_:1},8,["to","aria-label"])]),l("p",null,[r(a(e.$t("auth.sign_in_using"))+"  ",1),l("a",{id:"lnkSignWithWebauthn",role:"button",class:"is-link",onKeyup:A(b,["enter"]),onClick:b,tabindex:"0","aria-label":e.$t("auth.sign_in_using_security_device")},a(e.$t("auth.webauthn.security_device")),41,ae)]),t(g).disableRegistration==!1?(n(),u("p",ne,[r(a(e.$t("auth.forms.dont_have_account_yet"))+"  ",1),o(y,{id:"lnkRegister",to:{name:"register"},class:"is-link"},{default:f(()=>[r(a(e.$t("auth.register")),1)]),_:1})])):d("",!0),t(g).enableSso&&Object.values(t(c).config.sso).includes(!0)?(n(),u("div",oe,[l("div",le,a(e.$t("auth.or_continue_with")),1),l("div",re,[t(c).config.sso.openid?(n(),u("a",ue,[r(" OpenID"),o(w,{class:"ml-2",icon:["fab","openid"]})])):d("",!0),t(c).config.sso.github?(n(),u("a",de,[r(" Github"),o(w,{class:"ml-2",icon:["fab","github-alt"]})])):d("",!0)])])):d("",!0)])]),_:1})),o(N)],64)}}};export{fe as default};
