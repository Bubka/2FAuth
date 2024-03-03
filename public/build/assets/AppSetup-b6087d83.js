import{_ as O}from"./AdminTabs-e10d9edb.js";import{a4 as D,R as A,r as g,e as h,f as p,g as _,h as l,n as M,i as t,j as V,t as d,l as w,m as i,Q,a as q,b as F,a2 as J,a0 as K,o as P,I as E,p as B,F as X,G as Y}from"./app-32c6ab3e.js";import{_ as Z}from"./CopyButton-346411c3.js";/*! 2FAuth version 5.0.4 - Copyright (c) 2023 Bubka - https://github.com/Bubka/2FAuth */const $=D("api"),C={get(u={}){return $.get("/settings",{...u})},update(u,m){return $.put("/settings/"+u,{value:m})},delete(u,m={}){return $.delete("/settings/"+u,{...m})}},L=D("web"),T={getSystemInfos(u={}){return L.get("infos",{...u})},getLastRelease(u={}){return L.get("latestRelease",{...u})},sendTestEmail(u={}){return L.post("testEmail",{...u})}},ee={class:"columns is-mobile is-vcentered"},te={class:"column is-narrow"},se={class:"column"},ne={key:0,class:"mt-2 has-text-warning"},le=l("span",{class:"release-flag"},null,-1),ae=l("a",{class:"is-size-7",href:"https://github.com/Bubka/2FAuth/releases"},"View on Github",-1),oe={key:1,class:"has-text-grey"},ie={key:2,class:"has-text-grey"},re={__name:"VersionChecker",setup(u){const m=A(),v=g(!1),c=g();async function a(){v.value=!0,c.value=void 0,await T.getLastRelease({returnError:!0}).then(f=>{m.latestRelease=f.data.newRelease,c.value=f.data.newRelease===null?null:f.data.newRelease===!1}).catch(()=>{c.value=null}),v.value=!1}return(f,b)=>{const R=h("FontAwesomeIcon");return p(),_("div",ee,[l("div",te,[l("button",{type:"button",class:M([t(v)?"is-loading":"","button is-link is-rounded is-small"]),onClick:a},"Check now",2)]),l("div",se,[t(m).latestRelease?(p(),_("span",ne,[le,V(d(t(m).latestRelease)+" is available ",1),ae])):w("",!0),t(c)?(p(),_("span",oe,[i(R,{icon:["fas","check"],class:"mr-1 has-text-success"}),V(" "+d(f.$t("commons.you_are_up_to_date")),1)])):t(c)===null?(p(),_("span",ie,[i(R,{icon:["fas","times"],class:"mr-1 has-text-danger"}),V(d(f.$t("errors.check_failed_try_later")),1)])):w("",!0)])])}}},ue={class:"options-tabs"},de={class:"title is-4 pt-4 has-text-grey-light"},ce={class:"field"},me=["innerHTML"],pe=["innerHTML"],_e=["innerHTML"],fe={class:"columns is-mobile is-vcentered"},he={class:"column is-narrow"},ge={class:"icon is-small"},be={class:"title is-4 pt-4 has-text-grey-light"},ye={class:"title is-4 pt-4 has-text-grey-light"},ve={class:"title is-4 pt-5 has-text-grey-light"},Re={key:0,class:"about-debug box is-family-monospace is-size-7"},ke=["value"],Ve={class:"has-text-grey"},Se={key:1,class:"about-debug box is-family-monospace is-size-7 has-text-warning-dark"},Ce={__name:"AppSetup",setup(u){const m=Q("2fauth"),v=q(),c=F(),a=A(),f=J(m.prefix+"returnTo","accounts"),b=g(),R=g(null),S=g(!1),k=g({restrictList:null,restrictRule:null}),o=g({checkForUpdate:a.checkForUpdate,useEncryption:a.useEncryption,restrictRegistration:a.restrictRegistration,restrictList:a.restrictList,restrictRule:a.restrictRule,disableRegistration:a.disableRegistration,enableSso:a.enableSso});function y(n,e){k.value[n]=null,C.update(n,e).then(r=>{a[n]=e,F().success({type:"is-success",text:E("settings.forms.setting_saved")})}).catch(r=>{r.response.status===422?k.value[n]=r.response.data.message:c.error(r)})}function x(n,e){e==""?(k.value[n]=null,C.delete(n,{returnError:!0}).then(r=>{a[n]="",F().success({type:"is-success",text:E("settings.forms.setting_saved")})}).catch(r=>{r.response.status!==404&&c.error(r)})):y(n,e)}function H(){S.value=!0,T.sendTestEmail().finally(()=>{S.value=!1})}return K(n=>{n.name.startsWith("admin.")||c.clear()}),P(async()=>{C.get({returnError:!0}).then(n=>{o.value.restrictList="",o.value.restrictRule="",n.data.forEach(e=>{a[e.key]=e.value,o.value[e.key]=e.value})}).catch(n=>{c.alert({text:E("errors.data_cannot_be_refreshed_from_server")})}),T.getSystemInfos({returnError:!0}).then(n=>{b.value=n.data.common}).catch(()=>{b.value=null})}),(n,e)=>{const r=h("FormCheckbox"),z=h("FontAwesomeIcon"),U=h("FormField"),W=h("FormWrapper"),j=h("ButtonBackCloseCancel"),G=h("VueFooter");return p(),_("div",null,[i(O,{activeTab:"admin.appSetup"}),l("div",ue,[i(W,null,{default:B(()=>{var I;return[l("form",null,[l("h4",de,d(n.$t("settings.general")),1),i(r,{modelValue:t(o).checkForUpdate,"onUpdate:modelValue":[e[0]||(e[0]=s=>t(o).checkForUpdate=s),e[1]||(e[1]=s=>y("checkForUpdate",s))],fieldName:"checkForUpdate",label:"commons.check_for_update",help:"commons.check_for_update_help"},null,8,["modelValue"]),i(re),l("div",ce,[l("label",{class:"label",innerHTML:n.$t("admin.forms.test_email.label")},null,8,me),l("p",{class:"help",innerHTML:n.$t("admin.forms.test_email.help")},null,8,pe),l("p",{class:"help",innerHTML:n.$t("admin.forms.test_email.email_will_be_send_to_x",{email:t(v).email})},null,8,_e)]),l("div",fe,[l("div",he,[l("button",{type:"button",class:M([t(S)?"is-loading":"","button is-link is-rounded is-small"]),onClick:H},[l("span",ge,[i(z,{icon:["far","paper-plane"]})]),l("span",null,d(n.$t("commons.send")),1)],2)])]),l("h4",be,d(n.$t("settings.security")),1),i(r,{modelValue:t(o).useEncryption,"onUpdate:modelValue":[e[2]||(e[2]=s=>t(o).useEncryption=s),e[3]||(e[3]=s=>y("useEncryption",s))],fieldName:"useEncryption",label:"admin.forms.use_encryption.label",help:"admin.forms.use_encryption.help"},null,8,["modelValue"]),l("h4",ye,d(n.$t("admin.registrations")),1),i(r,{modelValue:t(o).restrictRegistration,"onUpdate:modelValue":[e[4]||(e[4]=s=>t(o).restrictRegistration=s),e[5]||(e[5]=s=>y("restrictRegistration",s))],fieldName:"restrictRegistration",isDisabled:t(a).disableRegistration,label:"admin.forms.restrict_registration.label",help:"admin.forms.restrict_registration.help"},null,8,["modelValue","isDisabled"]),i(U,{modelValue:t(o).restrictList,"onUpdate:modelValue":e[6]||(e[6]=s=>t(o).restrictList=s),"onChange:modelValue":e[7]||(e[7]=s=>x("restrictList",s)),fieldError:t(k).restrictList,fieldName:"restrictList",isDisabled:!t(a).restrictRegistration||t(a).disableRegistration,label:"admin.forms.restrict_list.label",help:"admin.forms.restrict_list.help",isIndented:!0},null,8,["modelValue","fieldError","isDisabled"]),i(U,{modelValue:t(o).restrictRule,"onUpdate:modelValue":e[8]||(e[8]=s=>t(o).restrictRule=s),"onChange:modelValue":e[9]||(e[9]=s=>x("restrictRule",s)),fieldError:t(k).restrictRule,fieldName:"restrictRule",isDisabled:!t(a).restrictRegistration||t(a).disableRegistration,label:"admin.forms.restrict_rule.label",help:"admin.forms.restrict_rule.help",isIndented:!0,leftIcon:"slash",rightIcon:"slash"},null,8,["modelValue","fieldError","isDisabled"]),i(r,{modelValue:t(o).disableRegistration,"onUpdate:modelValue":[e[10]||(e[10]=s=>t(o).disableRegistration=s),e[11]||(e[11]=s=>y("disableRegistration",s))],fieldName:"disableRegistration",label:"admin.forms.disable_registration.label",help:"admin.forms.disable_registration.help"},null,8,["modelValue"]),i(r,{modelValue:t(o).enableSso,"onUpdate:modelValue":[e[12]||(e[12]=s=>t(o).enableSso=s),e[13]||(e[13]=s=>y("enableSso",s))],fieldName:"enableSso",label:"admin.forms.enable_sso.label",help:"admin.forms.enable_sso.help"},null,8,["modelValue"])]),l("h4",ve,d(n.$t("commons.environment")),1),t(b)?(p(),_("div",Re,[i(Z,{id:"btnCopyEnvVars",token:(I=t(R))==null?void 0:I.innerText},null,8,["token"]),l("ul",{ref_key:"listInfos",ref:R,id:"listInfos"},[(p(!0),_(X,null,Y(t(b),(s,N)=>(p(),_("li",{value:s,key:N},[l("b",null,d(N),1),V(": "),l("span",Ve,d(s),1)],8,ke))),128))],512)])):t(b)===null?(p(),_("div",Se,d(n.$t("errors.error_during_data_fetching")),1)):w("",!0)]}),_:1})]),i(G,{showButtons:!0},{default:B(()=>[i(j,{returnTo:{name:t(f)},action:"close"},null,8,["returnTo"])]),_:1})])}}};export{Ce as default};
