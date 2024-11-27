import{a as _,c as F,d as V,j as r,k as b,l as n,m as y,e as x,f as o,G as B,p as g,H as C}from"./app-CzxRjCVM.js";import{F as E}from"./Form-EFIuWKGI.js";/*! 2FAuth version 5.4.3 - Copyright (c) 2024 Bubka - https://github.com/Bubka/2FAuth */const k={__name:"Create",setup(N){const m=_(),l=g(),e=F(new E({name:"",email:"",password:"",password_confirmation:"",is_admin:!1}));async function i(d){e.password_confirmation=e.password,e.post("/api/v1/users").then(a=>{const t=a.data;m.success({text:C("admin.user_created")}),l.push({name:"admin.manageUser",params:{userId:t.info.id}})})}return(d,a)=>{const t=n("FormField"),u=n("FormPasswordField"),p=n("FormCheckbox"),f=n("FormButtons"),c=n("FormWrapper"),w=n("VueFooter");return y(),V("div",null,[r(c,{title:"admin.new_user"},{default:b(()=>[x("form",{onSubmit:B(i,["prevent"]),onKeydown:a[4]||(a[4]=s=>o(e).onKeydown(s))},[r(t,{modelValue:o(e).name,"onUpdate:modelValue":a[0]||(a[0]=s=>o(e).name=s),fieldName:"name",fieldError:o(e).errors.get("name"),inputType:"text",label:"auth.forms.name",autocomplete:"username",maxLength:255,autofocus:""},null,8,["modelValue","fieldError"]),r(t,{modelValue:o(e).email,"onUpdate:modelValue":a[1]||(a[1]=s=>o(e).email=s),fieldName:"email",fieldError:o(e).errors.get("email"),inputType:"email",label:"auth.forms.email",autocomplete:"email",maxLength:255},null,8,["modelValue","fieldError"]),r(u,{modelValue:o(e).password,"onUpdate:modelValue":a[2]||(a[2]=s=>o(e).password=s),fieldName:"password",fieldError:o(e).errors.get("password"),showRules:!0,label:"auth.forms.password",autocomplete:"new-password"},null,8,["modelValue","fieldError"]),r(p,{modelValue:o(e).is_admin,"onUpdate:modelValue":a[3]||(a[3]=s=>o(e).is_admin=s),fieldName:"is_admin",label:"admin.forms.is_admin.label",help:"admin.forms.is_admin.help"},null,8,["modelValue"]),r(f,{isBusy:o(e).isBusy,isDisabled:o(e).isDisabled,showCancelButton:!0,cancelLandingView:"admin.users",caption:"commons.create",submitId:"btnCreateUser"},null,8,["isBusy","isDisabled"])],32)]),_:1}),r(w)])}}};export{k as default};
