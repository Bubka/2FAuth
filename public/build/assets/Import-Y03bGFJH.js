import{Y as J,Z as mt,l as _,m as l,d as r,n as b,j as d,i as f,e as t,f as o,g,t as i,h as N,T as Z,a as pt,u as ht,b as vt,r as k,c as D,H as v,x as H,s as gt,o as _t,J as yt,k as T,w as W,C as K,G as kt,F as Q,D as Y,U as bt,L as wt,P as $t}from"./app-DivTFvlm.js";import{F as P}from"./Form-CHg6aD9v.js";import{_ as xt}from"./OtpDisplay--p_2JRo2.js";import{S as Ct}from"./Spinner-D32cz8pt.js";import{u as At}from"./bus-BArM1eUg.js";/*! 2FAuth version 5.5.0 - Copyright (c) 2024 Bubka - https://github.com/Bubka/2FAuth */const Ft=["for"],It=["disabled","id","value","placeholder","maxlength","aria-describedby","aria-invalid","aria-errormessage"],St=["id","innerHTML"],Et=Object.assign({inheritAttrs:!1},{__name:"FormTextarea",props:{modelValue:[String,Number,Boolean],label:{type:String,default:""},fieldName:{type:String,default:"",required:!0},fieldError:[String],placeholder:{type:String,default:""},help:{type:String,default:""},size:{type:String,default:""},hasOffset:{type:Boolean,default:!1},isDisabled:{type:Boolean,default:!1},maxLength:{type:Number,default:null},isIndented:Boolean,isLocked:Boolean,leftIcon:"",rightIcon:"",idSuffix:{type:String,default:""}},setup(a){const w=a,{inputId:m}=J(w.inputType,w.fieldName+w.idSuffix),{valErrorId:B}=mt(w.fieldName),C=J("legend",w.fieldName).inputId;return($,p)=>{const x=_("FontAwesomeIcon"),A=_("FieldError");return l(),r("div",{class:b(["mb-3",{"pt-3":a.hasOffset,"is-flex":a.isIndented}])},[a.isIndented?(l(),r("div",{key:0,class:b(["mx-2 pr-1",{"is-opacity-5":a.isDisabled||a.isLocked}])},[d(x,{class:"has-text-grey",icon:["fas","chevron-right"],transform:"rotate-135"})],2)):f("",!0),t("div",{class:b(["field",{"is-flex-grow-5":a.isIndented}])},[a.label?(l(),r("label",{key:0,for:o(m),class:"label"},[g(i($.$t(a.label)),1),a.isLocked?(l(),N(x,{key:0,icon:["fas","lock"],class:"ml-2",size:"xs"})):f("",!0)],8,Ft)):f("",!0),t("div",{class:b(["control",{"has-icons-left":a.leftIcon,"has-icons-right":a.rightIcon}])},[t("textarea",Z({disabled:a.isDisabled||a.isLocked,id:o(m),class:["textarea",a.size],value:a.modelValue,placeholder:a.placeholder},$.$attrs,{onInput:p[0]||(p[0]=h=>$.$emit("update:modelValue",h.target.value)),onChange:p[1]||(p[1]=h=>$.$emit("change:modelValue",h.target.value)),maxlength:a.maxLength,"aria-describedby":a.help?o(C):void 0,"aria-invalid":a.fieldError!=null,"aria-errormessage":a.fieldError!=null?o(B):void 0}),null,16,It)],2),a.fieldError!=null?(l(),N(A,{key:1,error:a.fieldError,field:a.fieldName},null,8,["error","field"])):f("",!0),a.help?(l(),r("p",{key:2,id:o(C),class:"help",innerHTML:$.$t(a.help)},null,8,St)):f("",!0)],2)],2)}}}),zt={class:"title has-text-grey-dark"},Vt={key:0},Tt={class:"block is-size-7-mobile"},Nt={class:"mb-2"},qt={class:"columns"},Lt={class:"column"},Bt={class:"block"},Mt={class:"card"},Ot={class:"card-content"},Dt={class:"media"},Ht={class:"media-left"},Pt={class:"image is-32x32"},Ut={class:"media-content"},Rt=["innerHTML"],jt={class:"subtitle is-6 is-size-7-mobile"},Gt={class:"card-footer"},Jt={class:"block"},Wt={class:"card"},Kt={class:"card-content"},Qt={class:"media"},Yt={class:"media-left"},Zt={class:"image is-32x32"},Xt={class:"media-content"},te={class:"title is-5 has-text-grey"},ee={class:"subtitle is-6 is-size-7-mobile"},se={class:"card-footer"},oe={class:"block"},ie={class:"card"},le={class:"card-content"},ne={class:"media"},ae={class:"media-left"},re={class:"image is-32x32"},de={class:"media-content"},ce=["innerHTML"],ue={class:"subtitle is-6 is-size-7-mobile"},fe={class:"content"},me={class:"card-footer"},pe={class:"title is-5 has-text-grey-dark"},he={class:"block is-size-7-mobile"},ve={class:"table is-size-7-mobile is-fullwidth"},ge={key:1},_e={key:2},ye={class:"block is-size-7-mobile"},ke={class:"mb-2"},be={class:"is-flex is-justify-content-space-between"},we=["onClick","title"],$e=["src"],xe={key:1,class:"is-flex-grow-1 has-ellipsis"},Ce={key:2,class:"tags is-flex-wrap-nowrap"},Ae=["onClick","title"],Fe=["onClick","title"],Ie={key:3,class:"has-nowrap"},Se={key:0,class:"has-text-success"},Ee={key:1,class:"has-text-danger"},ze={class:"is-size-6 is-size-7-mobile"},Ve={class:"is-family-primary has-text-grey"},Te={key:0,class:"has-text-danger"},Ne={key:1,class:"has-text-warning"},qe={key:2},Le={key:0,class:"mt-2 is-size-7 is-pulled-right"},Be={key:1,class:"mt-2 is-size-7 is-pulled-right"},Me={key:0,class:"control"},Re={__name:"Import",setup(a){const w=wt("2fauth"),m=pt(),B=ht(),C=At(),$=vt(),p=k(null),x=k(null),A=k(null),h=k(null),q=k(null),I=D(new P({service:"",account:"",otp_type:"",icon:"",secret:"",algorithm:"",digits:null,counter:null,period:null})),S=D(new P({file:null,withSecret:!0})),E=D(new P({qrcode:null,withSecret:!0})),F=k(!1);v("twofaccounts.import.qr_code"),v("twofaccounts.import.plain_text");const c=k([]),y=k(!1),z=H(()=>c.value.filter(e=>e.imported==-1&&e.id>-2).length),U=H(()=>c.value.filter(e=>e.id===-1&&e.imported===-1).length),X=H(()=>c.value.filter(e=>e.imported===1).length);gt(F,e=>{var s;e==!1&&((s=p.value)==null||s.clearOTP())}),_t(()=>{C.migrationUri&&(M(C.migrationUri),C.migrationUri=null)});async function M(e){y.value=!0,await yt.migrate(e,{returnError:!0}).then(s=>{s.data.forEach(u=>{u.imported=-1,c.value.push(u)}),j(),h.value=q.value=null}).catch(s=>{m.alert({text:v(s.response.data.message)})}),y.value=!1}function tt(){var e;confirm(v("twofaccounts.confirm.discard_duplicates"))&&(m.clear(),(e=p.value)==null||e.clearOTP(),c.value=c.value.filter(s=>s.id!==-1))}function et(){var e;confirm(v("twofaccounts.confirm.discard_all"))&&(m.clear(),(e=p.value)==null||e.clearOTP(),c.value=[])}function st(e){confirm(v("twofaccounts.confirm.discard"))&&c.value.splice(e,1)}async function ot(){for(let e=0;e<c.value.length;e++)c.value[e].imported==-1&&await R(e)}async function R(e){I.fill(c.value[e]),await I.post("/api/v1/twofaccounts",{returnError:!0}).then(s=>{c.value[e].imported=1,c.value[e].id=s.data.id,delete s.data.secret,$.items.push(s.data)}).catch(s=>{c.value[e].imported=0,c.value[e].id=0,c.value[e].errors=I.errors.flatten()})}function it(e){I.fill(c.value[e]),F.value=!0,$t().then(()=>{p.value.show()})}function lt(){S.clear(),y.value=!0,S.file=x.value.files[0],S.upload("/api/v1/twofaccounts/migration",{returnError:!0}).then(e=>{e.data.forEach(s=>{s.imported=-1,c.value.push(s)}),j()}).catch(e=>{e.response.status===422?e.response.data.errors.file==null&&m.alert({text:v("errors.invalid_2fa_data")}):m.alert({text:e.response.data.message})}),y.value=!1}function nt(){E.clear(),y.value=!0,E.qrcode=A.value.files[0],E.upload("/api/v1/qrcode/decode",{returnError:!0}).then(e=>{M(e.data.data)}).catch(e=>{e.response.status===422?e.response.data.errors.qrcode==null&&m.alert({text:v("errors.invalid_2fa_data")}):m.alert({text:e.response.data.message})}),y.value=!1}function j(){m.success({text:v("twofaccounts.import.x_valid_accounts_found",{count:z.value})})}function at(){q.value=null,h.value?M(h.value):q.value=v("validation.required",{attribute:"Direct input"})}return(e,s)=>{const u=_("FontAwesomeIcon"),G=_("FieldError"),rt=_("RouterLink"),dt=_("ButtonBackCloseCancel"),ct=_("VueFooter"),ut=_("ResponsiveWidthWrapper"),ft=_("modal");return l(),N(o(bt),null,{default:T(({mode:L})=>[t("div",null,[d(ut,null,{default:T(()=>[t("h1",zt,i(e.$t("twofaccounts.import.import")),1),!o(y)&&o(c).length==0?(l(),r("div",Vt,[t("div",Tt,[t("p",Nt,i(e.$t("twofaccounts.import.import_legend")),1),t("p",null,i(e.$t("twofaccounts.import.import_legend_afterpart")),1)]),t("div",qt,[t("div",Lt,[t("div",Bt,[t("div",Mt,[t("div",Ot,[t("div",Dt,[t("div",Ht,[t("figure",Pt,[d(u,{icon:["fas","qrcode"],size:"2x",class:b(L=="dark"?"has-text-grey-darker":"has-text-grey-lighter")},null,8,["class"])])]),t("div",Ut,[t("p",{class:"title is-5 has-text-grey",innerHTML:e.$t("twofaccounts.import.qr_code")},null,8,Rt),t("p",jt,i(e.$t("twofaccounts.import.supported_formats_for_qrcode_upload")),1)])]),o(E).errors.hasAny("qrcode")?(l(),N(G,{key:0,error:o(E).errors.get("qrcode"),field:"qrcode"},null,8,["error"])):f("",!0)]),t("footer",Gt,[d(rt,{id:"btnCapture",to:{name:"capture"},class:"card-footer-item"},{default:T(()=>[g(i(e.$t("twofaccounts.import.scan")),1)]),_:1}),t("a",{role:"button",tabindex:"0",class:"card-footer-item is-relative",onClick:s[0]||(s[0]=n=>o(A).click()),onKeyup:s[1]||(s[1]=W(n=>o(A).click(),["enter"]))},[t("input",{inert:"",tabindex:"-1",class:"file-input",type:"file",accept:"image/*",onChange:nt,ref_key:"qrcodeInput",ref:A},null,544),g(" "+i(e.$t("twofaccounts.import.upload")),1)],32)])])]),t("div",Jt,[t("div",Wt,[t("div",Kt,[t("div",Qt,[t("div",Yt,[t("figure",Zt,[d(u,{icon:["fas","file-lines"],size:"2x",class:b(L=="dark"?"has-text-grey-darker":"has-text-grey-lighter")},null,8,["class"])])]),t("div",Xt,[t("p",te,i(e.$t("twofaccounts.import.text_file")),1),t("p",ee,i(e.$t("twofaccounts.import.supported_formats_for_file_upload")),1)])]),o(S).errors.hasAny("file")?(l(),N(G,{key:0,error:o(S).errors.get("file"),field:"file"},null,8,["error"])):f("",!0)]),t("footer",se,[t("a",{role:"button",tabindex:"0",class:"card-footer-item is-relative",onClick:s[2]||(s[2]=n=>o(x).click()),onKeyup:s[3]||(s[3]=W(n=>o(x).click(),["enter"]))},[t("input",{inert:"",tabindex:"-1",class:"file-input",type:"file",accept:"text/plain,application/json,text/csv,.2fas",onChange:lt,ref_key:"fileInput",ref:x},null,544),g(" "+i(e.$t("twofaccounts.import.upload")),1)],32)])])]),t("div",oe,[t("div",ie,[t("div",le,[t("div",ne,[t("div",ae,[t("figure",re,[d(u,{icon:["fas","align-left"],size:"2x",class:b(L=="dark"?"has-text-grey-darker":"has-text-grey-lighter")},null,8,["class"])])]),t("div",de,[t("p",{class:"title is-5 has-text-grey",innerHTML:e.$t("twofaccounts.import.direct_input")},null,8,ce),t("p",ue,i(e.$t("twofaccounts.import.expected_format_for_direct_input")),1)])]),t("div",fe,[d(Et,{modelValue:o(h),"onUpdate:modelValue":s[4]||(s[4]=n=>K(h)?h.value=n:null),fieldError:o(q),fieldName:"payload",rows:"5",size:"is-small"},null,8,["modelValue","fieldError"])])]),t("footer",me,[t("a",{role:"button",tabindex:"0",class:"card-footer-item is-relative",onClick:kt(at,["stop"])},i(e.$t("commons.submit")),1)])])])])]),t("h2",pe,i(e.$t("twofaccounts.import.supported_migration_formats")),1),t("div",he,[d(u,{icon:["fas","fa-triangle-exclamation"],class:"has-text-warning-dark"}),g(" "+i(e.$t("twofaccounts.import.do_not_set_password_or_encryption")),1)]),t("table",ve,[s[26]||(s[26]=t("thead",null,[t("tr",null,[t("th"),t("th",null,"Plain text"),t("th",null,"QR code"),t("th",null,"JSON")])],-1)),t("tbody",null,[t("tr",null,[s[12]||(s[12]=t("th",null,"Google Authenticator",-1)),s[13]||(s[13]=t("td",null,null,-1)),t("td",null,[d(u,{icon:["fas","circle-check"]})]),s[14]||(s[14]=t("td",null,null,-1))]),t("tr",null,[s[15]||(s[15]=t("th",null,"Aegis Auth",-1)),t("td",null,[d(u,{icon:["fas","circle-check"]})]),s[16]||(s[16]=t("td",null,null,-1)),t("td",null,[d(u,{icon:["fas","circle-check"]})])]),t("tr",null,[s[17]||(s[17]=t("th",null,"2FAS auth",-1)),s[18]||(s[18]=t("td",null,null,-1)),s[19]||(s[19]=t("td",null,null,-1)),t("td",null,[d(u,{icon:["fas","circle-check"]})])]),t("tr",null,[s[20]||(s[20]=t("th",null,"FreeOTP+",-1)),t("td",null,[d(u,{icon:["fas","circle-check"]})]),s[21]||(s[21]=t("td",null,null,-1)),s[22]||(s[22]=t("td",null,null,-1))]),t("tr",null,[s[23]||(s[23]=t("th",null,"2FAuth",-1)),s[24]||(s[24]=t("td",null,null,-1)),s[25]||(s[25]=t("td",null,null,-1)),t("td",null,[d(u,{icon:["fas","circle-check"]})])])])])])):o(y)&&o(c).length===0?(l(),r("div",ge,[d(Ct,{type:"fullscreen-overlay",isVisible:!0,message:"twofaccounts.import.parsing_data"})])):(l(),r("div",_e,[t("div",ye,[t("p",ke,i(e.$t("twofaccounts.import.submitted_data_parsed_now_accounts_are_awaiting_import")),1),t("p",null,i(e.$t("twofaccounts.import.use_buttons_to_save_or_discard")),1)]),(l(!0),r(Q,null,Y(o(c),(n,O)=>(l(),r("div",{key:n.name,class:"group-item is-size-5 is-size-6-mobile"},[t("div",be,[n.id>-2&&n.imported!==0?(l(),r("div",{key:0,class:"is-flex-grow-1 has-ellipsis is-clickable",onClick:V=>it(O),title:e.$t("twofaccounts.import.generate_a_test_password")},[n.icon&&o(B).preferences.showAccountsIcons?(l(),r("img",{key:0,role:"presentation",class:"import-icon",src:o(w).config.subdirectory+"/storage/icons/"+n.icon,alt:""},null,8,$e)):f("",!0),g(" "+i(n.account),1)],8,we)):(l(),r("div",xe,i(n.account),1)),n.imported===-1?(l(),r("div",Ce,[t("button",{type:"button",class:b(["button tag",{"is-dark has-text-grey-light":L=="dark"}]),onClick:V=>st(O),title:e.$t("twofaccounts.import.discard_this_account")},[d(u,{icon:["fas","trash"]})],10,Ae),n.id>-2?(l(),r("button",{key:0,type:"button",class:"button tag is-link",onClick:V=>R(O),title:e.$t("twofaccounts.import.import_this_account")},i(e.$t("twofaccounts.import.to_import")),9,Fe)):f("",!0)])):(l(),r("div",Ie,[n.imported===1?(l(),r("span",Se,[g(i(e.$t("twofaccounts.import.imported"))+" ",1),d(u,{icon:["fas","check"]})])):(l(),r("span",Ee,[g(i(e.$t("twofaccounts.import.failure"))+" ",1),d(u,{icon:["fas","times"]})]))]))]),t("div",ze,[t("div",Ve,i(e.$t("twofaccounts.import.issuer"))+": "+i(n.service),1),n.id===-2?(l(),r("div",Te,[d(u,{class:"mr-1",icon:["fas","times-circle"]}),g(i(n.secret),1)])):f("",!0),n.id===-1&&n.imported!==1&&!n.errors?(l(),r("div",Ne,[d(u,{class:"mr-1",icon:["fas","exclamation-circle"]}),g(i(e.$t("twofaccounts.import.possible_duplicate")),1)])):f("",!0),n.errors?(l(),r("ul",qe,[(l(!0),r(Q,null,Y(n.errors,V=>(l(),r("li",{key:V,class:"has-text-danger"},i(V),1))),128))])):f("",!0)])]))),128)),o(z)>0?(l(),r("div",Le,[o(U)?(l(),r("button",{key:0,onClick:s[5]||(s[5]=n=>tt()),type:"button",class:"has-text-grey button is-small is-ghost"},i(e.$t("twofaccounts.import.discard_duplicates"))+" ("+i(o(U))+")",1)):f("",!0),t("button",{onClick:s[6]||(s[6]=n=>et()),type:"button",class:"has-text-grey button is-small is-ghost"},i(e.$t("twofaccounts.import.discard_all")),1)])):f("",!0),o(X)==o(c).length?(l(),r("div",Be,[t("button",{onClick:s[7]||(s[7]=n=>c.value=[]),type:"button",class:"has-text-grey button is-small is-ghost"},i(e.$t("commons.clear")),1)])):f("",!0)])),d(ct,{showButtons:!0},{default:T(()=>[o(z)>0?(l(),r("p",Me,[t("button",{type:"button",class:"button is-link is-rounded is-focus",onClick:ot},[t("span",null,i(e.$t("twofaccounts.import.import_all"))+" ("+i(o(z))+")",1)])])):f("",!0),d(dt,{returnTo:{name:"accounts"},action:o(z)>0?"cancel":"close"},null,8,["action"])]),_:1})]),_:2},1024),d(ft,{modelValue:o(F),"onUpdate:modelValue":s[11]||(s[11]=n=>K(F)?F.value=n:null)},{default:T(()=>[d(xt,Z({ref_key:"otpDisplay",ref:p},o(I).data(),{onIncrementHotp:s[8]||(s[8]=()=>{}),onValidationError:s[9]||(s[9]=()=>{}),onPleaseCloseMe:s[10]||(s[10]=n=>F.value=!1)}),null,16)]),_:1},8,["modelValue"])])]),_:1})}}};export{Re as default};
//# sourceMappingURL=Import-Y03bGFJH.js.map
