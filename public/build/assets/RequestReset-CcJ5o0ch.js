import{a as f,c as h,a0 as _,h as w,k as F,_ as b,l as r,m as y,e as B,j as l,f as s,G as V}from"./app-DivTFvlm.js";import{F as v}from"./Form-CHg6aD9v.js";/*! 2FAuth version 5.5.0 - Copyright (c) 2024 Bubka - https://github.com/Bubka/2FAuth */const N={__name:"RequestReset",setup(R){const o=f(),a=b().name=="webauthn.lost",t=h(new v({email:""}));function i(n){o.clear(),t.post(a?"/webauthn/lost":"/user/password/lost",{returnError:!0}).then(e=>{o.success({text:e.data.message,duration:-1})}).catch(e=>{e.response.data.requestFailed?o.alert({text:e.response.data.requestFailed,duration:-1}):e.response.status!==422&&o.error(e)})}return _(()=>{o.clear()}),(n,e)=>{const m=r("FormField"),c=r("FormButtons"),d=r("VueFooter"),p=r("FormWrapper");return y(),w(p,{title:n.$t(a?"auth.webauthn.account_recovery":"auth.forms.reset_password"),punchline:n.$t(a?"auth.webauthn.recovery_punchline":"auth.forms.reset_punchline")},{default:F(()=>[B("form",{onSubmit:V(i,["prevent"]),onKeydown:e[1]||(e[1]=u=>s(t).onKeydown(u))},[l(m,{modelValue:s(t).email,"onUpdate:modelValue":e[0]||(e[0]=u=>s(t).email=u),fieldName:"email",fieldError:s(t).errors.get("email"),label:"auth.forms.email",autofocus:""},null,8,["modelValue","fieldError"]),l(c,{submitId:"btnSendResetPwd",isBusy:s(t).isBusy,caption:n.$t(a?"auth.webauthn.send_recovery_link":"auth.forms.send_password_reset_link"),showCancelButton:!0,cancelLandingView:"login"},null,8,["isBusy","caption"])],32),l(d)]),_:1},8,["title","punchline"])}}};export{N as default};
//# sourceMappingURL=RequestReset-CcJ5o0ch.js.map
