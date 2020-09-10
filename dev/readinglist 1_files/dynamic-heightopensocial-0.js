var gadgets=gadgets||{};
gadgets.config=function(){var A=[];
return{register:function(D,C,B){var E=A[D];
if(!E){E=[];
A[D]=E
}E.push({validators:C||{},callback:B})
},get:function(B){if(B){return configuration[B]||{}
}return configuration
},init:function(D,K){configuration=D;
for(var B in A){if(A.hasOwnProperty(B)){var C=A[B],H=D[B];
for(var G=0,F=C.length;
G<F;
++G){var I=C[G];
if(H&&!K){var E=I.validators;
for(var J in E){if(E.hasOwnProperty(J)){if(!E[J](H[J])){throw new Error('Invalid config value "'+H[J]+'" for parameter "'+J+'" in component "'+B+'"')
}}}}if(I.callback){I.callback(D)
}}}}},EnumValidator:function(E){var D=[];
if(arguments.length>1){for(var C=0,B;
(B=arguments[C]);
++C){D.push(B)
}}else{D=E
}return function(G){for(var F=0,H;
(H=D[F]);
++F){if(G===D[F]){return true
}}}
},RegExValidator:function(B){return function(C){return B.test(C)
}
},ExistsValidator:function(B){return typeof B!=="undefined"
},NonEmptyStringValidator:function(B){return typeof B==="string"&&B.length>0
},BooleanValidator:function(B){return typeof B==="boolean"
},LikeValidator:function(B){return function(D){for(var E in B){if(B.hasOwnProperty(E)){var C=B[E];
if(!C(D[E])){return false
}}}return true
}
}}
}();;
var gadgets=gadgets||{};
gadgets.util=function(){function G(L){var M;
var K=L;
var I=K.indexOf("?");
var J=K.indexOf("#");
if(J===-1){M=K.substr(I+1)
}else{M=[K.substr(I+1,J-I-1),"&",K.substr(J+1)].join("")
}return M.split("&")
}var E=null;
var D={};
var C={};
var F=[];
var A={0:false,10:true,13:true,34:true,39:true,60:true,62:true,92:true,8232:true,8233:true};
function B(I,J){return String.fromCharCode(J)
}function H(I){D=I["core.util"]||{}
}if(gadgets.config){gadgets.config.register("core.util",null,H)
}return{getUrlParameters:function(Q){if(E!==null&&typeof Q==="undefined"){return E
}var M={};
E={};
var J=G(Q||document.location.href);
var O=window.decodeURIComponent?decodeURIComponent:unescape;
for(var L=0,K=J.length;
L<K;
++L){var N=J[L].indexOf("=");
if(N===-1){continue
}var I=J[L].substring(0,N);
var P=J[L].substring(N+1);
P=P.replace(/\+/g," ");
M[I]=O(P)
}if(typeof Q==="undefined"){E=M
}return M
},makeClosure:function(L,N,M){var K=[];
for(var J=2,I=arguments.length;
J<I;
++J){K.push(arguments[J])
}return function(){var O=K.slice();
for(var Q=0,P=arguments.length;
Q<P;
++Q){O.push(arguments[Q])
}return N.apply(L,O)
}
},makeEnum:function(J){var L={};
for(var K=0,I;
(I=J[K]);
++K){L[I]=I
}return L
},getFeatureParameters:function(I){return typeof D[I]==="undefined"?null:D[I]
},hasFeature:function(I){return typeof D[I]!=="undefined"
},getServices:function(){return C
},registerOnLoadHandler:function(I){F.push(I)
},runOnLoadHandlers:function(){for(var J=0,I=F.length;
J<I;
++J){F[J]()
}},escape:function(I,M){if(!I){return I
}else{if(typeof I==="string"){return gadgets.util.escapeString(I)
}else{if(typeof I==="array"){for(var L=0,J=I.length;
L<J;
++L){I[L]=gadgets.util.escape(I[L])
}}else{if(typeof I==="object"&&M){var K={};
for(var N in I){if(I.hasOwnProperty(N)){K[gadgets.util.escapeString(N)]=gadgets.util.escape(I[N],true)
}}return K
}}}}return I
},escapeString:function(M){var J=[],L,N;
for(var K=0,I=M.length;
K<I;
++K){L=M.charCodeAt(K);
N=A[L];
if(N===true){J.push("&#",L,";")
}else{if(N!==false){J.push(M.charAt(K))
}}}return J.join("")
},unescapeString:function(I){return I.replace(/&#([0-9]+);/g,B)
}}
}();
gadgets.util.getUrlParameters();;
var tamings___=tamings___||[];
tamings___.push(function(A){caja___.whitelistFuncs([[gadgets.util,"escapeString"],[gadgets.util,"getFeatureParameters"],[gadgets.util,"hasFeature"],[gadgets.util,"registerOnLoadHandler"],[gadgets.util,"unescapeString"]])
});;
var gadgets=gadgets||{};
if(window.JSON&&window.JSON.parse&&window.JSON.stringify){gadgets.json={parse:function(B){try{return window.JSON.parse(B)
}catch(A){return false
}},stringify:function(B){try{return window.JSON.stringify(B)
}catch(A){return null
}}}
}else{gadgets.json=function(){function f(n){return n<10?"0"+n:n
}Date.prototype.toJSON=function(){return[this.getUTCFullYear(),"-",f(this.getUTCMonth()+1),"-",f(this.getUTCDate()),"T",f(this.getUTCHours()),":",f(this.getUTCMinutes()),":",f(this.getUTCSeconds()),"Z"].join("")
};
var m={"\b":"\\b","\t":"\\t","\n":"\\n","\f":"\\f","\r":"\\r",'"':'\\"',"\\":"\\\\"};
function stringify(value){var a,i,k,l,r=/["\\\x00-\x1f\x7f-\x9f]/g,v;
switch(typeof value){case"string":return r.test(value)?'"'+value.replace(r,function(a){var c=m[a];
if(c){return c
}c=a.charCodeAt();
return"\\u00"+Math.floor(c/16).toString(16)+(c%16).toString(16)
})+'"':'"'+value+'"';
case"number":return isFinite(value)?String(value):"null";
case"boolean":case"null":return String(value);
case"object":if(!value){return"null"
}a=[];
if(typeof value.length==="number"&&!value.propertyIsEnumerable("length")){l=value.length;
for(i=0;
i<l;
i+=1){a.push(stringify(value[i])||"null")
}return"["+a.join(",")+"]"
}for(k in value){if(k.match("___$")){continue
}if(value.hasOwnProperty(k)){if(typeof k==="string"){v=stringify(value[k]);
if(v){a.push(stringify(k)+":"+v)
}}}}return"{"+a.join(",")+"}"
}}return{stringify:stringify,parse:function(text){if(/^[\],:{}\s]*$/.test(text.replace(/\\["\\\/b-u]/g,"@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,"]").replace(/(?:^|:|,)(?:\s*\[)+/g,""))){return eval("("+text+")")
}return false
}}
}()
};;
var tamings___=tamings___||[];
tamings___.push(function(A){caja___.whitelistFuncs([[gadgets.json,"parse"],[gadgets.json,"stringify"]])
});;
var opensocial=opensocial||{};
opensocial.requestSendMessage=function(A,D,B,C){return opensocial.Container.get().requestSendMessage(A,D,B,C)
};
opensocial.requestShareApp=function(A,D,B,C){opensocial.Container.get().requestShareApp(A,D,B,C)
};
opensocial.requestCreateActivity=function(C,B,A){if(!C||(!C.getField(opensocial.Activity.Field.TITLE)&&!C.getField(opensocial.Activity.Field.TITLE_ID))){if(A){window.setTimeout(function(){A(new opensocial.ResponseItem(null,null,opensocial.ResponseItem.Error.BAD_REQUEST,"You must pass in an activity with a title or title id."))
},0)
}return 
}opensocial.Container.get().requestCreateActivity(C,B,A)
};
opensocial.CreateActivityPriority={HIGH:"HIGH",LOW:"LOW"};
opensocial.hasPermission=function(A){return opensocial.Container.get().hasPermission(A)
};
opensocial.requestPermission=function(B,C,A){opensocial.Container.get().requestPermission(B,C,A)
};
opensocial.Permission={VIEWER:"viewer"};
opensocial.getEnvironment=function(){return opensocial.Container.get().getEnvironment()
};
opensocial.newDataRequest=function(){return opensocial.Container.get().newDataRequest()
};
opensocial.newActivity=function(A){return opensocial.Container.get().newActivity(A)
};
opensocial.newMediaItem=function(C,A,B){return opensocial.Container.get().newMediaItem(C,A,B)
};
opensocial.newMessage=function(A,B){return opensocial.Container.get().newMessage(A,B)
};
opensocial.EscapeType={HTML_ESCAPE:"htmlEscape",NONE:"none"};
opensocial.newIdSpec=function(A){return opensocial.Container.get().newIdSpec(A)
};
opensocial.newNavigationParameters=function(A){return opensocial.Container.get().newNavigationParameters(A)
};
opensocial.invalidateCache=function(){opensocial.Container.get().invalidateCache()
};
Function.prototype.inherits=function(A){function B(){}B.prototype=A.prototype;
this.superClass_=A.prototype;
this.prototype=new B();
this.prototype.constructor=this
};;
opensocial.Activity=function(A){this.fields_=A
};
opensocial.Activity.Field={TITLE_ID:"titleId",TITLE:"title",TEMPLATE_PARAMS:"templateParams",URL:"url",MEDIA_ITEMS:"mediaItems",BODY_ID:"bodyId",BODY:"body",EXTERNAL_ID:"externalId",STREAM_TITLE:"streamTitle",STREAM_URL:"streamUrl",STREAM_SOURCE_URL:"streamSourceUrl",STREAM_FAVICON_URL:"streamFaviconUrl",PRIORITY:"priority",ID:"id",USER_ID:"userId",APP_ID:"appId",POSTED_TIME:"postedTime"};
opensocial.Activity.prototype.getId=function(){return this.getField(opensocial.Activity.Field.ID)
};
opensocial.Activity.prototype.getField=function(A,B){return opensocial.Container.getField(this.fields_,A,B)
};
opensocial.Activity.prototype.setField=function(A,B){return(this.fields_[A]=B)
};;
opensocial.Address=function(A){this.fields_=A||{}
};
opensocial.Address.Field={TYPE:"type",UNSTRUCTURED_ADDRESS:"unstructuredAddress",PO_BOX:"poBox",STREET_ADDRESS:"streetAddress",EXTENDED_ADDRESS:"extendedAddress",REGION:"region",LOCALITY:"locality",POSTAL_CODE:"postalCode",COUNTRY:"country",LATITUDE:"latitude",LONGITUDE:"longitude"};
opensocial.Address.prototype.getField=function(A,B){return opensocial.Container.getField(this.fields_,A,B)
};;
opensocial.BodyType=function(A){this.fields_=A||{}
};
opensocial.BodyType.Field={BUILD:"build",HEIGHT:"height",WEIGHT:"weight",EYE_COLOR:"eyeColor",HAIR_COLOR:"hairColor"};
opensocial.BodyType.prototype.getField=function(A,B){return opensocial.Container.getField(this.fields_,A,B)
};;
opensocial.Collection=function(C,B,A){this.array_=C||[];
this.offset_=B||0;
this.totalSize_=A||this.array_.length
};
opensocial.Collection.prototype.getById=function(C){for(var A=0;
A<this.size();
A++){var B=this.array_[A];
if(B.getId()===C){return B
}}return null
};
opensocial.Collection.prototype.size=function(){return this.array_.length
};
opensocial.Collection.prototype.each=function(B){for(var A=0;
A<this.size();
A++){B(this.array_[A])
}};
opensocial.Collection.prototype.asArray=function(){return this.array_
};
opensocial.Collection.prototype.getTotalSize=function(){return this.totalSize_
};
opensocial.Collection.prototype.getOffset=function(){return this.offset_
};;
opensocial.Container=function(){};
opensocial.Container.container_=null;
opensocial.Container.setContainer=function(A){opensocial.Container.container_=A
};
opensocial.Container.get=function(){return opensocial.Container.container_
};
opensocial.Container.prototype.getEnvironment=function(){};
opensocial.Container.prototype.requestSendMessage=function(A,D,B,C){gadgets.rpc.call(null,"requestSendMessage",B,A,D,B,C)
};
opensocial.Container.prototype.requestShareApp=function(A,D,B,C){if(B){window.setTimeout(function(){B(new opensocial.ResponseItem(null,null,opensocial.ResponseItem.Error.NOT_IMPLEMENTED,null))
},0)
}};
opensocial.Container.prototype.requestCreateActivity=function(C,B,A){if(A){window.setTimeout(function(){A(new opensocial.ResponseItem(null,null,opensocial.ResponseItem.Error.NOT_IMPLEMENTED,null))
},0)
}};
opensocial.Container.prototype.hasPermission=function(A){return false
};
opensocial.Container.prototype.requestPermission=function(B,C,A){if(A){window.setTimeout(function(){A(new opensocial.ResponseItem(null,null,opensocial.ResponseItem.Error.NOT_IMPLEMENTED,null))
},0)
}};
opensocial.Container.prototype.requestData=function(A,B){};
opensocial.Container.prototype.newFetchPersonRequest=function(B,A){};
opensocial.Container.prototype.newFetchPeopleRequest=function(A,B){};
opensocial.Container.prototype.newFetchPersonAppDataRequest=function(A,C,B){};
opensocial.Container.prototype.newUpdatePersonAppDataRequest=function(A,B){};
opensocial.Container.prototype.newRemovePersonAppDataRequest=function(A){};
opensocial.Container.prototype.newFetchActivitiesRequest=function(A,B){};
opensocial.Container.prototype.newFetchMessageCollectionsRequest=function(A,B){};
opensocial.Container.prototype.newFetchMessagesRequest=function(A,C,B){};
opensocial.Container.prototype.newCollection=function(C,B,A){return new opensocial.Collection(C,B,A)
};
opensocial.Container.prototype.newPerson=function(A,B,C){return new opensocial.Person(A,B,C)
};
opensocial.Container.prototype.newActivity=function(A){return new opensocial.Activity(A)
};
opensocial.Container.prototype.newMediaItem=function(C,A,B){return new opensocial.MediaItem(C,A,B)
};
opensocial.Container.prototype.newMessage=function(A,B){return new opensocial.Message(A,B)
};
opensocial.Container.prototype.newIdSpec=function(A){return new opensocial.IdSpec(A)
};
opensocial.Container.prototype.newNavigationParameters=function(A){return new opensocial.NavigationParameters(A)
};
opensocial.Container.prototype.newResponseItem=function(A,C,B,D){return new opensocial.ResponseItem(A,C,B,D)
};
opensocial.Container.prototype.newDataResponse=function(A,B){return new opensocial.DataResponse(A,B)
};
opensocial.Container.prototype.newDataRequest=function(){return new opensocial.DataRequest()
};
opensocial.Container.prototype.newEnvironment=function(B,A){return new opensocial.Environment(B,A)
};
opensocial.Container.prototype.invalidateCache=function(){};
opensocial.Container.isArray=function(A){return A instanceof Array
};
opensocial.Container.getField=function(A,B,C){var D=A[B];
return opensocial.Container.escape(D,C,false)
};
opensocial.Container.escape=function(C,B,A){if(B&&B[opensocial.DataRequest.DataRequestFields.ESCAPE_TYPE]==opensocial.EscapeType.NONE){return C
}else{return gadgets.util.escape(C,A)
}};;
opensocial.DataRequest=function(){this.requestObjects_=[]
};
opensocial.DataRequest.prototype.requestObjects_=null;
opensocial.DataRequest.prototype.getRequestObjects=function(){return this.requestObjects_
};
opensocial.DataRequest.prototype.add=function(B,A){return this.requestObjects_.push({key:A,request:B})
};
opensocial.DataRequest.prototype.send=function(A){var B=A||function(){};
opensocial.Container.get().requestData(this,B)
};
opensocial.DataRequest.SortOrder={TOP_FRIENDS:"topFriends",NAME:"name"};
opensocial.DataRequest.FilterType={ALL:"all",HAS_APP:"hasApp",TOP_FRIENDS:"topFriends",IS_FRIENDS_WITH:"isFriendsWith"};
opensocial.DataRequest.PeopleRequestFields={PROFILE_DETAILS:"profileDetail",SORT_ORDER:"sortOrder",FILTER:"filter",FILTER_OPTIONS:"filterOptions",FIRST:"first",MAX:"max",APP_DATA:"appData",ESCAPE_TYPE:"escapeType"};
opensocial.DataRequest.prototype.addDefaultParam=function(C,B,A){C[B]=C[B]||A
};
opensocial.DataRequest.prototype.addDefaultProfileFields=function(B){var A=opensocial.DataRequest.PeopleRequestFields;
var C=B[A.PROFILE_DETAILS]||[];
B[A.PROFILE_DETAILS]=C.concat([opensocial.Person.Field.ID,opensocial.Person.Field.NAME,opensocial.Person.Field.THUMBNAIL_URL])
};
opensocial.DataRequest.prototype.asArray=function(A){if(opensocial.Container.isArray(A)){return A
}else{return[A]
}};
opensocial.DataRequest.prototype.newFetchPersonRequest=function(B,A){A=A||{};
this.addDefaultProfileFields(A);
return opensocial.Container.get().newFetchPersonRequest(B,A)
};
opensocial.DataRequest.prototype.newFetchPeopleRequest=function(B,C){C=C||{};
var A=opensocial.DataRequest.PeopleRequestFields;
this.addDefaultProfileFields(C);
this.addDefaultParam(C,A.SORT_ORDER,opensocial.DataRequest.SortOrder.TOP_FRIENDS);
this.addDefaultParam(C,A.FILTER,opensocial.DataRequest.FilterType.ALL);
this.addDefaultParam(C,A.FIRST,0);
this.addDefaultParam(C,A.MAX,20);
return opensocial.Container.get().newFetchPeopleRequest(B,C)
};
opensocial.DataRequest.DataRequestFields={ESCAPE_TYPE:"escapeType"};
opensocial.DataRequest.prototype.newFetchPersonAppDataRequest=function(A,C,B){return opensocial.Container.get().newFetchPersonAppDataRequest(A,this.asArray(C),B)
};
opensocial.DataRequest.prototype.newUpdatePersonAppDataRequest=function(A,B){return opensocial.Container.get().newUpdatePersonAppDataRequest(A,B)
};
opensocial.DataRequest.prototype.newRemovePersonAppDataRequest=function(A){return opensocial.Container.get().newRemovePersonAppDataRequest(A)
};
opensocial.DataRequest.ActivityRequestFields={APP_ID:"appId",FIRST:"first",MAX:"max"};
opensocial.DataRequest.prototype.newFetchActivitiesRequest=function(B,C){C=C||{};
var A=opensocial.DataRequest.ActivityRequestFields;
this.addDefaultParam(C,A.FIRST,0);
this.addDefaultParam(C,A.MAX,20);
return opensocial.Container.get().newFetchActivitiesRequest(B,C)
};
opensocial.DataRequest.prototype.newFetchMessageCollectionsRequest=function(A,B){B=B||{};
return opensocial.Container.get().newFetchMessageCollectionsRequest(A,B)
};
opensocial.DataRequest.prototype.newFetchMessagesRequest=function(A,C,B){B=B||{};
return opensocial.Container.get().newFetchMessagesRequest(A,C,B)
};;
opensocial.DataResponse=function(A,B,C){this.responseItems_=A;
this.globalError_=B;
this.errorMessage_=C
};
opensocial.DataResponse.prototype.hadError=function(){return !!this.globalError_
};
opensocial.DataResponse.prototype.getErrorMessage=function(){return this.errorMessage_
};
opensocial.DataResponse.prototype.get=function(A){return this.responseItems_[A]
};;
opensocial.Email=function(A){this.fields_=A||{}
};
opensocial.Email.Field={TYPE:"type",ADDRESS:"address"};
opensocial.Email.prototype.getField=function(A,B){return opensocial.Container.getField(this.fields_,A,B)
};;
opensocial.Enum=function(B,A){this.key=B;
this.displayValue=A
};
opensocial.Enum.prototype.getKey=function(){return gadgets.util.escape(this.key)
};
opensocial.Enum.prototype.getDisplayValue=function(){return gadgets.util.escape(this.displayValue)
};
opensocial.Enum.Smoker={NO:"NO",YES:"YES",SOCIALLY:"SOCIALLY",OCCASIONALLY:"OCCASIONALLY",REGULARLY:"REGULARLY",HEAVILY:"HEAVILY",QUITTING:"QUITTING",QUIT:"QUIT"};
opensocial.Enum.Drinker={NO:"NO",YES:"YES",SOCIALLY:"SOCIALLY",OCCASIONALLY:"OCCASIONALLY",REGULARLY:"REGULARLY",HEAVILY:"HEAVILY",QUITTING:"QUITTING",QUIT:"QUIT"};
opensocial.Enum.Gender={MALE:"MALE",FEMALE:"FEMALE"};
opensocial.Enum.LookingFor={DATING:"DATING",FRIENDS:"FRIENDS",RELATIONSHIP:"RELATIONSHIP",NETWORKING:"NETWORKING",ACTIVITY_PARTNERS:"ACTIVITY_PARTNERS",RANDOM:"RANDOM"};
opensocial.Enum.Presence={AWAY:"AWAY",CHAT:"CHAT",DND:"DND",OFFLINE:"OFFLINE",ONLINE:"ONLINE",XA:"XA"};;
opensocial.Environment=function(B,A){this.domain=B;
this.supportedFields=A
};
opensocial.Environment.prototype.getDomain=function(){return this.domain
};
opensocial.Environment.ObjectType={PERSON:"person",ADDRESS:"address",BODY_TYPE:"bodyType",EMAIL:"email",NAME:"name",ORGANIZATION:"organization",PHONE:"phone",URL:"url",ACTIVITY:"activity",MEDIA_ITEM:"mediaItem",MESSAGE:"message",MESSAGE_TYPE:"messageType",SORT_ORDER:"sortOrder",FILTER_TYPE:"filterType"};
opensocial.Environment.prototype.supportsField=function(A,C){var B=this.supportedFields[A]||[];
return !!B[C]
};;
opensocial.IdSpec=function(A){this.fields_=A||{}
};
opensocial.IdSpec.Field={USER_ID:"userId",GROUP_ID:"groupId",NETWORK_DISTANCE:"networkDistance"};
opensocial.IdSpec.PersonId={OWNER:"OWNER",VIEWER:"VIEWER"};
opensocial.IdSpec.GroupId={SELF:"SELF",FRIENDS:"FRIENDS",ALL:"ALL"};
opensocial.IdSpec.prototype.getField=function(A,B){return opensocial.Container.getField(this.fields_,A,B)
};
opensocial.IdSpec.prototype.setField=function(A,B){return(this.fields_[A]=B)
};;
opensocial.MediaItem=function(D,B,C){this.fields_={};
if(C){for(var A in C){if(C.hasOwnProperty(A)){this.fields_[A]=C[A]
}}}this.fields_[opensocial.MediaItem.Field.MIME_TYPE]=D;
this.fields_[opensocial.MediaItem.Field.URL]=B
};
opensocial.MediaItem.Type={IMAGE:"image",VIDEO:"video",AUDIO:"audio"};
opensocial.MediaItem.Field={TYPE:"type",MIME_TYPE:"mimeType",URL:"url"};
opensocial.MediaItem.prototype.getField=function(A,B){return opensocial.Container.getField(this.fields_,A,B)
};
opensocial.MediaItem.prototype.setField=function(A,B){return(this.fields_[A]=B)
};;
opensocial.MessageCollection=function(A){this.fields_=A||{}
};
opensocial.MessageCollection.Field={ID:"id",TITLE:"title",TOTAL:"total",UNREAD:"unread",UPDATED:"updated",URLS:"urls"};
opensocial.MessageCollection.prototype.getField=function(A,B){return opensocial.Container.getField(this.fields_,A,B)
};
opensocial.MessageCollection.prototype.setField=function(A,B){return this.fields_[A]=B
};;
opensocial.Message=function(A,B){if(typeof A=="string"){this.fields_=B||{};
this.fields_[opensocial.Message.Field.BODY]=A
}else{this.fields_=A||{}
}};
opensocial.Message.Field={APP_URL:"appUrl",BODY:"body",BODY_ID:"bodyId",COLLECTION_IDS:"collectionIds",ID:"id",PARENT_ID:"parentId",RECIPIENTS:"recipients",SENDER_ID:"senderId",STATUS:"status",TIME_SENT:"timeSent",TITLE:"title",TITLE_ID:"titleId",TYPE:"type",UPDATED:"updated",URLS:"urls"};
opensocial.Message.Type={EMAIL:"email",NOTIFICATION:"notification",PRIVATE_MESSAGE:"privateMessage",PUBLIC_MESSAGE:"publicMessage"};
opensocial.Message.Status={NEW:"new",DELETED:"deleted",FLAGGED:"flagged"};
opensocial.Message.prototype.getField=function(A,B){return opensocial.Container.getField(this.fields_,A,B)
};
opensocial.Message.prototype.setField=function(A,B){return(this.fields_[A]=B)
};;
opensocial.Name=function(A){this.fields_=A||{}
};
opensocial.Name.Field={FAMILY_NAME:"familyName",GIVEN_NAME:"givenName",ADDITIONAL_NAME:"additionalName",HONORIFIC_PREFIX:"honorificPrefix",HONORIFIC_SUFFIX:"honorificSuffix",UNSTRUCTURED:"unstructured"};
opensocial.Name.prototype.getField=function(A,B){return opensocial.Container.getField(this.fields_,A,B)
};;
opensocial.NavigationParameters=function(A){this.fields_=A||{}
};
opensocial.NavigationParameters.Field={VIEW:"view",OWNER:"owner",PARAMETERS:"parameters"};
opensocial.NavigationParameters.prototype.getField=function(A,B){return opensocial.Container.getField(this.fields_,A,B)
};
opensocial.NavigationParameters.prototype.setField=function(A,B){return(this.fields_[A]=B)
};
opensocial.NavigationParameters.DestinationType={VIEWER_DESTINATION:"viewerDestination",RECIPIENT_DESTINATION:"recipientDestination"};;
opensocial.Organization=function(A){this.fields_=A||{}
};
opensocial.Organization.Field={NAME:"name",TITLE:"title",DESCRIPTION:"description",FIELD:"field",SUB_FIELD:"subField",START_DATE:"startDate",END_DATE:"endDate",SALARY:"salary",ADDRESS:"address",WEBPAGE:"webpage"};
opensocial.Organization.prototype.getField=function(A,B){return opensocial.Container.getField(this.fields_,A,B)
};;
opensocial.Person=function(A,B,C){this.fields_=A||{};
this.isOwner_=B;
this.isViewer_=C
};
opensocial.Person.Field={ID:"id",NAME:"name",NICKNAME:"nickname",THUMBNAIL_URL:"thumbnailUrl",PROFILE_URL:"profileUrl",CURRENT_LOCATION:"currentLocation",ADDRESSES:"addresses",EMAILS:"emails",PHONE_NUMBERS:"phoneNumbers",ABOUT_ME:"aboutMe",STATUS:"status",PROFILE_SONG:"profileSong",PROFILE_VIDEO:"profileVideo",GENDER:"gender",SEXUAL_ORIENTATION:"sexualOrientation",RELATIONSHIP_STATUS:"relationshipStatus",AGE:"age",DATE_OF_BIRTH:"dateOfBirth",BODY_TYPE:"bodyType",ETHNICITY:"ethnicity",SMOKER:"smoker",DRINKER:"drinker",CHILDREN:"children",PETS:"pets",LIVING_ARRANGEMENT:"livingArrangement",TIME_ZONE:"timeZone",LANGUAGES_SPOKEN:"languagesSpoken",JOBS:"jobs",JOB_INTERESTS:"jobInterests",SCHOOLS:"schools",INTERESTS:"interests",URLS:"urls",MUSIC:"music",MOVIES:"movies",TV_SHOWS:"tvShows",BOOKS:"books",ACTIVITIES:"activities",SPORTS:"sports",HEROES:"heroes",QUOTES:"quotes",CARS:"cars",FOOD:"food",TURN_ONS:"turnOns",TURN_OFFS:"turnOffs",TAGS:"tags",ROMANCE:"romance",SCARED_OF:"scaredOf",HAPPIEST_WHEN:"happiestWhen",FASHION:"fashion",HUMOR:"humor",LOOKING_FOR:"lookingFor",RELIGION:"religion",POLITICAL_VIEWS:"politicalViews",HAS_APP:"hasApp",NETWORK_PRESENCE:"networkPresence"};
opensocial.Person.prototype.getId=function(){return this.getField(opensocial.Person.Field.ID)
};
var ORDERED_NAME_FIELDS_=[opensocial.Name.Field.HONORIFIC_PREFIX,opensocial.Name.Field.GIVEN_NAME,opensocial.Name.Field.FAMILY_NAME,opensocial.Name.Field.HONORIFIC_SUFFIX,opensocial.Name.Field.ADDITIONAL_NAME];
opensocial.Person.prototype.getDisplayName=function(){var B=this.getField(opensocial.Person.Field.NAME);
if(B){var E=B.getField(opensocial.Name.Field.UNSTRUCTURED);
if(E){return E
}var D="";
for(var C=0;
C<ORDERED_NAME_FIELDS_.length;
C++){var A=B.getField(ORDERED_NAME_FIELDS_[C]);
if(A){D+=A+" "
}}return D.replace(/^\s+|\s+$/g,"")
}return this.getField(opensocial.Person.Field.NICKNAME)
};
opensocial.Person.prototype.getField=function(A,B){return opensocial.Container.getField(this.fields_,A,B)
};
opensocial.Person.prototype.getAppData=function(A){};
opensocial.Person.prototype.isViewer=function(){return !!this.isViewer_
};
opensocial.Person.prototype.isOwner=function(){return !!this.isOwner_
};;
opensocial.Phone=function(A){this.fields_=A||{}
};
opensocial.Phone.Field={TYPE:"type",NUMBER:"number"};
opensocial.Phone.prototype.getField=function(A,B){return opensocial.Container.getField(this.fields_,A,B)
};;
opensocial.ResponseItem=function(A,C,B,D){this.originalDataRequest_=A;
this.data_=C;
this.errorCode_=B;
this.errorMessage_=D
};
opensocial.ResponseItem.prototype.hadError=function(){return !!this.errorCode_
};
opensocial.ResponseItem.Error={NOT_IMPLEMENTED:"notImplemented",UNAUTHORIZED:"unauthorized",FORBIDDEN:"forbidden",BAD_REQUEST:"badRequest",INTERNAL_ERROR:"internalError",LIMIT_EXCEEDED:"limitExceeded"};
opensocial.ResponseItem.prototype.getErrorCode=function(){return this.errorCode_
};
opensocial.ResponseItem.prototype.getErrorMessage=function(){return this.errorMessage_
};
opensocial.ResponseItem.prototype.getOriginalDataRequest=function(){return this.originalDataRequest_
};
opensocial.ResponseItem.prototype.getData=function(){return this.data_
};;
opensocial.Url=function(A){this.fields_=A||{}
};
opensocial.Url.Field={TYPE:"type",LINK_TEXT:"linkText",ADDRESS:"address"};
opensocial.Url.prototype.getField=function(A,B){return opensocial.Container.getField(this.fields_,A,B)
};;
var tamings___=tamings___||[];
tamings___.push(function(A){___.grantRead(opensocial.IdSpec,"PersonId");
___.grantRead(opensocial.DataRequest,"PeopleRequestFields");
___.grantRead(JsonRpcRequestItem,"rpc");
___.grantRead(JsonRpcRequestItem,"processData");
___.grantRead(JsonRpcRequestItem,"processResponse");
___.grantRead(JsonRpcRequestItem,"errors");
caja___.whitelistCtors([[window,"JsonRpcRequestItem",Object],[opensocial,"Activity",Object],[opensocial,"Address",Object],[opensocial,"BodyType",Object],[opensocial,"Container",Object],[opensocial,"Collection",Object],[opensocial,"DataRequest",Object],[opensocial,"DataResponse",Object],[opensocial,"Email",Object],[opensocial,"Enum",Object],[opensocial,"Environment",Object],[opensocial,"IdSpec",Object],[opensocial,"MediaItem",Object],[opensocial,"Message",Object],[opensocial,"Name",Object],[opensocial,"NavigationParameters",Object],[opensocial,"Organization",Object],[opensocial,"Person",Object],[opensocial,"Phone",Object],[opensocial,"ResponseItem",Object],[opensocial,"Url",Object]]);
caja___.whitelistMeths([[opensocial.Activity,"getField"],[opensocial.Activity,"getId"],[opensocial.Activity,"setField"],[opensocial.Address,"getField"],[opensocial.BodyType,"getField"],[opensocial.Container,"getEnvironment"],[opensocial.Container,"requestSendMessage"],[opensocial.Container,"requestShareApp"],[opensocial.Container,"requestCreateActivity"],[opensocial.Container,"hasPermission"],[opensocial.Container,"requestPermission"],[opensocial.Container,"requestData"],[opensocial.Container,"newFetchPersonRequest"],[opensocial.Container,"newFetchPeopleRequest"],[opensocial.Container,"newFetchPersonAppDataRequest"],[opensocial.Container,"newUpdatePersonAppDataRequest"],[opensocial.Container,"newRemovePersonAppDataRequest"],[opensocial.Container,"newFetchActivitiesRequest"],[opensocial.Container,"newFetchMessageCollectionsRequest"],[opensocial.Container,"newFetchMessagesRequest"],[opensocial.Container,"newCollection"],[opensocial.Container,"newPerson"],[opensocial.Container,"newActivity"],[opensocial.Container,"newMediaItem"],[opensocial.Container,"newMessage"],[opensocial.Container,"newIdSpec"],[opensocial.Container,"newNavigationParameters"],[opensocial.Container,"newResponseItem"],[opensocial.Container,"newDataResponse"],[opensocial.Container,"newDataRequest"],[opensocial.Container,"newEnvironment"],[opensocial.Container,"invalidateCache"],[opensocial.Collection,"asArray"],[opensocial.Collection,"each"],[opensocial.Collection,"getById"],[opensocial.Collection,"getOffset"],[opensocial.Collection,"getTotalSize"],[opensocial.Collection,"size"],[opensocial.DataRequest,"add"],[opensocial.DataRequest,"newFetchActivitiesRequest"],[opensocial.DataRequest,"newFetchPeopleRequest"],[opensocial.DataRequest,"newFetchPersonAppDataRequest"],[opensocial.DataRequest,"newFetchPersonRequest"],[opensocial.DataRequest,"newRemovePersonAppDataRequest"],[opensocial.DataRequest,"newUpdatePersonAppDataRequest"],[opensocial.DataRequest,"send"],[opensocial.DataResponse,"get"],[opensocial.DataResponse,"getErrorMessage"],[opensocial.DataResponse,"hadError"],[opensocial.Email,"getField"],[opensocial.Enum,"getDisplayValue"],[opensocial.Enum,"getKey"],[opensocial.Environment,"getDomain"],[opensocial.Environment,"supportsField"],[opensocial.IdSpec,"getField"],[opensocial.IdSpec,"setField"],[opensocial.MediaItem,"getField"],[opensocial.MediaItem,"setField"],[opensocial.Message,"getField"],[opensocial.Message,"setField"],[opensocial.Name,"getField"],[opensocial.NavigationParameters,"getField"],[opensocial.NavigationParameters,"setField"],[opensocial.Organization,"getField"],[opensocial.Person,"getDisplayName"],[opensocial.Person,"getField"],[opensocial.Person,"getId"],[opensocial.Person,"isOwner"],[opensocial.Person,"isViewer"],[opensocial.Phone,"getField"],[opensocial.ResponseItem,"getData"],[opensocial.ResponseItem,"getErrorCode"],[opensocial.ResponseItem,"getErrorMessage"],[opensocial.ResponseItem,"getOriginalDataRequest"],[opensocial.ResponseItem,"hadError"],[opensocial.Url,"getField"]]);
caja___.whitelistFuncs([[opensocial.Container,"setContainer"],[opensocial.Container,"get"],[opensocial.Container,"getField"],[opensocial,"getEnvironment"],[opensocial,"hasPermission"],[opensocial,"newActivity"],[opensocial,"newDataRequest"],[opensocial,"newIdSpec"],[opensocial,"newMediaItem"],[opensocial,"newMessage"],[opensocial,"newNavigationParameters"],[opensocial,"requestCreateActivity"],[opensocial,"requestPermission"],[opensocial,"requestSendMessage"],[opensocial,"requestShareApp"]])
});;
var gadgets=gadgets||{};
gadgets.log=function(A){gadgets.log.logAtLevel(gadgets.log.INFO,A)
};
gadgets.warn=function(A){gadgets.log.logAtLevel(gadgets.log.WARNING,A)
};
gadgets.error=function(A){gadgets.log.logAtLevel(gadgets.log.ERROR,A)
};
gadgets.setLogLevel=function(A){gadgets.log.logLevelThreshold_=A
};
gadgets.log.logAtLevel=function(D,C){if(D<gadgets.log.logLevelThreshold_||!gadgets.log._console){return 
}var B;
var A=gadgets.log._console;
if(D==gadgets.log.WARNING&&A.warn){A.warn(C)
}else{if(D==gadgets.log.ERROR&&A.error){A.error(C)
}else{if(A.log){A.log(C)
}}}};
gadgets.log.INFO=1;
gadgets.log.WARNING=2;
gadgets.log.NONE=4;
gadgets.log.logLevelThreshold_=gadgets.log.INFO;
gadgets.log._console=window.console?window.console:window.opera?window.opera.postError:undefined;;
var tamings___=tamings___||[];
tamings___.push(function(A){___.grantRead(gadgets.log,"INFO");
___.grantRead(gadgets.log,"WARNING");
___.grantRead(gadgets.log,"ERROR");
___.grantRead(gadgets.log,"NONE");
caja___.whitelistFuncs([[gadgets,"log"],[gadgets,"warn"],[gadgets,"error"],[gadgets,"setLogLevel"],[gadgets.log,"logAtLevel"],])
});;
var gadgets=gadgets||{};
(function(){var I=null;
var J={};
var F=gadgets.util.escapeString;
var D={};
var H={};
var E="en";
var B="US";
var A=0;
function C(){var L=gadgets.util.getUrlParameters();
for(var K in L){if(L.hasOwnProperty(K)){if(K.indexOf("up_")===0&&K.length>3){J[K.substr(3)]=String(L[K])
}else{if(K==="country"){B=L[K]
}else{if(K==="lang"){E=L[K]
}else{if(K==="mid"){A=L[K]
}}}}}}}function G(){for(var K in H){if(typeof J[K]==="undefined"){J[K]=H[K]
}}}gadgets.Prefs=function(){if(!I){C();
G();
I=this
}return I
};
gadgets.Prefs.setInternal_=function(M,O){var N=false;
if(typeof M==="string"){if(!J.hasOwnProperty(M)||J[M]!==O){N=true
}J[M]=O
}else{for(var L in M){if(M.hasOwnProperty(L)){var K=M[L];
if(!J.hasOwnProperty(L)||J[L]!==K){N=true
}J[L]=K
}}}return N
};
gadgets.Prefs.setMessages_=function(K){D=K
};
gadgets.Prefs.setDefaultPrefs_=function(K){H=K
};
gadgets.Prefs.prototype.getString=function(K){if(K===".lang"){K="lang"
}return J[K]?F(J[K]):""
};
gadgets.Prefs.prototype.setDontEscape_=function(){F=function(K){return K
}
};
gadgets.Prefs.prototype.getInt=function(K){var L=parseInt(J[K],10);
return isNaN(L)?0:L
};
gadgets.Prefs.prototype.getFloat=function(K){var L=parseFloat(J[K]);
return isNaN(L)?0:L
};
gadgets.Prefs.prototype.getBool=function(K){var L=J[K];
if(L){return L==="true"||L===true||!!parseInt(L,10)
}return false
};
gadgets.Prefs.prototype.set=function(K,L){throw new Error("setprefs feature required to make this call.")
};
gadgets.Prefs.prototype.getArray=function(N){var O=J[N];
if(O){var K=O.split("|");
for(var M=0,L=K.length;
M<L;
++M){K[M]=F(K[M].replace(/%7C/g,"|"))
}return K
}return[]
};
gadgets.Prefs.prototype.setArray=function(K,L){throw new Error("setprefs feature required to make this call.")
};
gadgets.Prefs.prototype.getMsg=function(K){return D[K]||""
};
gadgets.Prefs.prototype.getCountry=function(){return B
};
gadgets.Prefs.prototype.getLang=function(){return E
};
gadgets.Prefs.prototype.getModuleId=function(){return A
}
})();;
var tamings___=tamings___||[];
tamings___.push(function(A){caja___.whitelistCtors([[gadgets,"Prefs",Object]]);
caja___.whitelistMeths([[gadgets.Prefs,"getArray"],[gadgets.Prefs,"getBool"],[gadgets.Prefs,"getCountry"],[gadgets.Prefs,"getFloat"],[gadgets.Prefs,"getInt"],[gadgets.Prefs,"getLang"],[gadgets.Prefs,"getMsg"],[gadgets.Prefs,"getString"],[gadgets.Prefs,"set"],[gadgets.Prefs,"setArray"]])
});;
var gadgets=gadgets||{};
gadgets.io=function(){var config={};
var oauthState;
function makeXhr(){var x;
if(window.ActiveXObject){x=new ActiveXObject("Msxml2.XMLHTTP");
if(!x){x=new ActiveXObject("Microsoft.XMLHTTP")
}return x
}else{if(window.XMLHttpRequest){return new window.XMLHttpRequest()
}}}function hadError(xobj,callback){if(xobj.readyState!==4){return true
}try{if(xobj.status!==200){var error=(""+xobj.status);
if(xobj.responseText){error=error+" "+xobj.responseText
}callback({errors:[error],rc:xobj.status,text:xobj.responseText});
return true
}}catch(e){callback({errors:[e.number+" Error not specified"],rc:e.number,text:e.description});
return true
}return false
}function processNonProxiedResponse(url,callback,params,xobj){if(hadError(xobj,callback)){return 
}var data={body:xobj.responseText};
callback(transformResponseData(params,data))
}var UNPARSEABLE_CRUFT="throw 1; < don't be evil' >";
function processResponse(url,callback,params,xobj){if(hadError(xobj,callback)){return 
}var txt=xobj.responseText;
txt=txt.substr(UNPARSEABLE_CRUFT.length);
var data=eval("("+txt+")");
data=data[url];
if(data.oauthState){oauthState=data.oauthState
}if(data.st){shindig.auth.updateSecurityToken(data.st)
}callback(transformResponseData(params,data))
}function transformResponseData(params,data){var resp={text:data.body,rc:data.rc||200,headers:data.headers,oauthApprovalUrl:data.oauthApprovalUrl,oauthError:data.oauthError,oauthErrorText:data.oauthErrorText,errors:[]};
if(resp.rc<200||resp.rc>206){resp.errors=[resp.rc+" Error"]
}else{if(resp.text){switch(params.CONTENT_TYPE){case"JSON":case"FEED":resp.data=gadgets.json.parse(resp.text);
if(!resp.data){resp.errors.push("500 Failed to parse JSON");
resp.rc=500;
resp.data=null
}break;
case"DOM":var dom;
if(window.ActiveXObject){dom=new ActiveXObject("Microsoft.XMLDOM");
dom.async=false;
dom.validateOnParse=false;
dom.resolveExternals=false;
if(!dom.loadXML(resp.text)){resp.errors.push("500 Failed to parse XML");
resp.rc=500
}else{resp.data=dom
}}else{var parser=new DOMParser();
dom=parser.parseFromString(resp.text,"text/xml");
if("parsererror"===dom.documentElement.nodeName){resp.errors.push("500 Failed to parse XML");
resp.rc=500
}else{resp.data=dom
}}break;
default:resp.data=resp.text;
break
}}}return resp
}function makeXhrRequest(realUrl,proxyUrl,callback,paramData,method,params,processResponseFunction,opt_contentType){var xhr=makeXhr();
if(proxyUrl.indexOf("//")==0){proxyUrl=document.location.protocol+proxyUrl
}xhr.open(method,proxyUrl,true);
if(callback){xhr.onreadystatechange=gadgets.util.makeClosure(null,processResponseFunction,realUrl,callback,params,xhr)
}if(paramData!==null){xhr.setRequestHeader("Content-Type",opt_contentType||"application/x-www-form-urlencoded");
xhr.send(paramData)
}else{xhr.send(null)
}}function respondWithPreload(postData,params,callback){if(gadgets.io.preloaded_&&postData.httpMethod==="GET"){for(var i=0;
i<gadgets.io.preloaded_.length;
i++){var preload=gadgets.io.preloaded_[i];
if(preload&&(preload.id===postData.url)){delete gadgets.io.preloaded_[i];
if(preload.rc!==200){callback({rc:preload.rc,errors:[preload.rc+" Error"]})
}else{if(preload.oauthState){oauthState=preload.oauthState
}var resp={body:preload.body,rc:preload.rc,headers:preload.headers,oauthApprovalUrl:preload.oauthApprovalUrl,oauthError:preload.oauthError,oauthErrorText:preload.oauthErrorText,errors:[]};
callback(transformResponseData(params,resp))
}return true
}}}return false
}function init(configuration){config=configuration["core.io"]||{}
}var requiredConfig={proxyUrl:new gadgets.config.RegExValidator(/.*%(raw)?url%.*/),jsonProxyUrl:gadgets.config.NonEmptyStringValidator};
gadgets.config.register("core.io",requiredConfig,init);
return{makeRequest:function(url,callback,opt_params){var params=opt_params||{};
var httpMethod=params.METHOD||"GET";
var refreshInterval=params.REFRESH_INTERVAL;
var auth,st;
if(params.AUTHORIZATION&&params.AUTHORIZATION!=="NONE"){auth=params.AUTHORIZATION.toLowerCase();
st=shindig.auth.getSecurityToken()
}else{if(httpMethod==="GET"&&refreshInterval===undefined){refreshInterval=3600
}}var signOwner=true;
if(typeof params.OWNER_SIGNED!=="undefined"){signOwner=params.OWNER_SIGNED
}var signViewer=true;
if(typeof params.VIEWER_SIGNED!=="undefined"){signViewer=params.VIEWER_SIGNED
}var headers=params.HEADERS||{};
if(httpMethod==="POST"&&!headers["Content-Type"]){headers["Content-Type"]="application/x-www-form-urlencoded"
}var urlParams=gadgets.util.getUrlParameters();
var paramData={url:url,httpMethod:httpMethod,headers:gadgets.io.encodeValues(headers,false),postData:params.POST_DATA||"",authz:auth||"",st:st||"",contentType:params.CONTENT_TYPE||"TEXT",numEntries:params.NUM_ENTRIES||"3",getSummaries:!!params.GET_SUMMARIES,signOwner:signOwner,signViewer:signViewer,gadget:urlParams.url,container:urlParams.container||urlParams.synd||"default",bypassSpecCache:gadgets.util.getUrlParameters().nocache||""};
if(auth==="oauth"||auth==="signed"){if(gadgets.io.oauthReceivedCallbackUrl_){paramData.OAUTH_RECEIVED_CALLBACK=gadgets.io.oauthReceivedCallbackUrl_;
gadgets.io.oauthReceivedCallbackUrl_=null
}paramData.oauthState=oauthState||"";
for(opt in params){if(params.hasOwnProperty(opt)){if(opt.indexOf("OAUTH_")===0){paramData[opt]=params[opt]
}}}}var proxyUrl=config.jsonProxyUrl.replace("%host%",document.location.host);
if(!respondWithPreload(paramData,params,callback,processResponse)){if(httpMethod==="GET"&&refreshInterval>0){var extraparams="?refresh="+refreshInterval+"&"+gadgets.io.encodeValues(paramData);
makeXhrRequest(url,proxyUrl+extraparams,callback,null,"GET",params,processResponse)
}else{makeXhrRequest(url,proxyUrl,callback,gadgets.io.encodeValues(paramData),"POST",params,processResponse)
}}},makeNonProxiedRequest:function(relativeUrl,callback,opt_params,opt_contentType){var params=opt_params||{};
makeXhrRequest(relativeUrl,relativeUrl,callback,params.POST_DATA,params.METHOD,params,processNonProxiedResponse,opt_contentType)
},clearOAuthState:function(){oauthState=undefined
},encodeValues:function(fields,opt_noEscaping){var escape=!opt_noEscaping;
var buf=[];
var first=false;
for(var i in fields){if(fields.hasOwnProperty(i)){if(!first){first=true
}else{buf.push("&")
}buf.push(escape?encodeURIComponent(i):i);
buf.push("=");
buf.push(escape?encodeURIComponent(fields[i]):fields[i])
}}return buf.join("")
},getProxyUrl:function(url,opt_params){var params=opt_params||{};
var refresh=params.REFRESH_INTERVAL;
if(refresh===undefined){refresh="3600"
}var urlParams=gadgets.util.getUrlParameters();
var rewriteMimeParam=params.rewriteMime?"&rewriteMime="+encodeURIComponent(params.rewriteMime):"";
return config.proxyUrl.replace("%url%",encodeURIComponent(url)).replace("%host%",document.location.host).replace("%rawurl%",url).replace("%refresh%",encodeURIComponent(refresh)).replace("%gadget%",encodeURIComponent(urlParams.url)).replace("%container%",encodeURIComponent(urlParams.container||urlParams.synd)).replace("%rewriteMime%",rewriteMimeParam)
}}
}();
gadgets.io.RequestParameters=gadgets.util.makeEnum(["METHOD","CONTENT_TYPE","POST_DATA","HEADERS","AUTHORIZATION","NUM_ENTRIES","GET_SUMMARIES","REFRESH_INTERVAL","OAUTH_SERVICE_NAME","OAUTH_USE_TOKEN","OAUTH_TOKEN_NAME","OAUTH_REQUEST_TOKEN","OAUTH_REQUEST_TOKEN_SECRET","OAUTH_RECEIVED_CALLBACK"]);
gadgets.io.MethodType=gadgets.util.makeEnum(["GET","POST","PUT","DELETE","HEAD"]);
gadgets.io.ContentType=gadgets.util.makeEnum(["TEXT","DOM","JSON","FEED"]);
gadgets.io.AuthorizationType=gadgets.util.makeEnum(["NONE","SIGNED","OAUTH"]);;
var tamings___=tamings___||[];
tamings___.push(function(A){caja___.whitelistFuncs([[gadgets.io,"encodeValues"],[gadgets.io,"getProxyUrl"],[gadgets.io,"makeRequest"]])
});;
var FieldTranslations={};
FieldTranslations.translateServerPersonToJsPerson=function(H,B){if(H.emails){for(var E=0;
E<H.emails.length;
E++){H.emails[E].address=H.emails[E].value
}}if(H.phoneNumbers){for(var A=0;
A<H.phoneNumbers.length;
A++){H.phoneNumbers[A].number=H.phoneNumbers[A].value
}}if(H.birthday){H.dateOfBirth=H.birthday
}if(H.utcOffset){H.timeZone=H.utcOffset
}if(H.addresses){for(var D=0;
D<H.addresses.length;
D++){H.addresses[D].unstructuredAddress=H.addresses[D].formatted
}}if(H.gender){var F=H.gender=="male"?"MALE":(H.gender=="female")?"FEMALE":null;
H.gender={key:F,displayValue:H.gender}
}FieldTranslations.translateUrlJson(H.profileSong);
FieldTranslations.translateUrlJson(H.profileVideo);
if(H.urls){for(var G=0;
G<H.urls.length;
G++){FieldTranslations.translateUrlJson(H.urls[G])
}}FieldTranslations.translateEnumJson(H.drinker);
FieldTranslations.translateEnumJson(H.lookingFor);
FieldTranslations.translateEnumJson(H.networkPresence);
FieldTranslations.translateEnumJson(H.smoker);
if(H.organizations){H.jobs=[];
H.schools=[];
for(var C=0;
C<H.organizations.length;
C++){var I=H.organizations[C];
if(I.type=="job"){H.jobs.push(I)
}else{if(I.type=="school"){H.schools.push(I)
}}}}if(H.name){H.name.unstructured=H.name.formatted
}if(H.appData){H.appData=opensocial.Container.escape(H.appData,B,true)
}};
FieldTranslations.translateEnumJson=function(A){if(A){A.key=A.value
}};
FieldTranslations.translateUrlJson=function(A){if(A){A.address=A.value
}};
FieldTranslations.translateJsPersonFieldsToServerFields=function(A){for(var B=0;
B<A.length;
B++){if(A[B]=="dateOfBirth"){A[B]="birthday"
}else{if(A[B]=="timeZone"){A[B]="utcOffset"
}else{if(A[B]=="jobs"){A[B]="organizations"
}else{if(A[B]=="schools"){A[B]="organizations"
}}}}}A.push("id");
A.push("displayName")
};
FieldTranslations.translateIsoStringToDate=function(A){var C="([0-9]{4})(-([0-9]{2})(-([0-9]{2})(T([0-9]{2}):([0-9]{2})(:([0-9]{2})(.([0-9]+))?)?(Z|(([-+])([0-9]{2}):([0-9]{2})))?)?)?)?";
var E=A.match(new RegExp(C));
var D=0;
var B=new Date(E[1],0,1);
if(E[3]){B.setMonth(E[3]-1)
}if(E[5]){B.setDate(E[5])
}if(E[7]){B.setHours(E[7])
}if(E[8]){B.setMinutes(E[8])
}if(E[10]){B.setSeconds(E[10])
}if(E[12]){B.setMilliseconds(Number("0."+E[12])*1000)
}if(E[14]){D=(Number(E[16])*60)+Number(E[17]);
D*=((E[15]=="-")?1:-1)
}D-=B.getTimezoneOffset();
time=(Number(B)+(D*60*1000));
return new Date(Number(time))
};
FieldTranslations.addAppDataAsProfileFields=function(D){if(D){if(D.appData){var A=D.appData;
if(typeof A==="string"){A=[A]
}var C=D.profileDetail||[];
for(var B=0;
B<A.length;
B++){if(A[B]==="*"){C.push("appData")
}else{C.push("appData."+A[B])
}}D.appData=A
}}};
FieldTranslations.translateStandardArguments=function(B,A){if(B.first){A.startIndex=B.first
}if(B.max){A.count=B.max
}if(B.sortOrder){A.sortBy=B.sortOrder
}if(B.filter){A.filterBy=B.filter
}if(B.filterOp){A.filterOp=B.filterOp
}if(B.filterValue){A.filterValue=B.filterValue
}if(B.fields){A.fields=B.fields
}};
FieldTranslations.translateNetworkDistance=function(A,B){if(A.getField("networkDistance")){B.networkDistance=A.getField("networkDistance")
}};;
var JsonActivity=function(A,B){A=A||{};
if(!B){JsonActivity.constructArrayObject(A,"mediaItems",JsonMediaItem)
}opensocial.Activity.call(this,A)
};
JsonActivity.inherits(opensocial.Activity);
JsonActivity.prototype.toJsonObject=function(){var C=JsonActivity.copyFields(this.fields_);
var D=C.mediaItems||[];
var A=[];
for(var B=0;
B<D.length;
B++){A[B]=D[B].toJsonObject()
}C.mediaItems=A;
return C
};
var JsonMediaItem=function(A){opensocial.MediaItem.call(this,A.mimeType,A.url,A)
};
JsonMediaItem.inherits(opensocial.MediaItem);
JsonMediaItem.prototype.toJsonObject=function(){return JsonActivity.copyFields(this.fields_)
};
JsonActivity.constructArrayObject=function(D,E,B){var C=D[E];
if(C){for(var A=0;
A<C.length;
A++){C[A]=new B(C[A])
}}};
JsonActivity.copyFields=function(A){var B={};
for(var C in A){B[C]=A[C]
}return B
};;
var JsonPerson=function(A){A=A||{};
JsonPerson.constructObject(A,"bodyType",opensocial.BodyType);
JsonPerson.constructObject(A,"currentLocation",opensocial.Address);
JsonPerson.constructObject(A,"name",opensocial.Name);
JsonPerson.constructObject(A,"profileSong",opensocial.Url);
JsonPerson.constructObject(A,"profileVideo",opensocial.Url);
JsonPerson.constructDate(A,"dateOfBirth");
JsonPerson.constructArrayObject(A,"addresses",opensocial.Address);
JsonPerson.constructArrayObject(A,"emails",opensocial.Email);
JsonPerson.constructArrayObject(A,"jobs",opensocial.Organization);
JsonPerson.constructArrayObject(A,"phoneNumbers",opensocial.Phone);
JsonPerson.constructArrayObject(A,"schools",opensocial.Organization);
JsonPerson.constructArrayObject(A,"urls",opensocial.Url);
JsonPerson.constructEnum(A,"gender");
JsonPerson.constructEnum(A,"smoker");
JsonPerson.constructEnum(A,"drinker");
JsonPerson.constructEnum(A,"networkPresence");
JsonPerson.constructEnumArray(A,"lookingFor");
opensocial.Person.call(this,A,A.isOwner,A.isViewer)
};
JsonPerson.inherits(opensocial.Person);
JsonPerson.constructEnum=function(B,C){var A=B[C];
if(A){B[C]=new opensocial.Enum(A.key,A.displayValue)
}};
JsonPerson.constructEnumArray=function(C,D){var B=C[D];
if(B){for(var A=0;
A<B.length;
A++){B[A]=new opensocial.Enum(B[A].key,B[A].displayValue)
}}};
JsonPerson.constructObject=function(C,D,A){var B=C[D];
if(B){C[D]=new A(B)
}};
JsonPerson.constructDate=function(B,C){var A=B[C];
if(A){B[C]=FieldTranslations.translateIsoStringToDate(A)
}};
JsonPerson.constructArrayObject=function(D,E,B){var C=D[E];
if(C){for(var A=0;
A<C.length;
A++){C[A]=new B(C[A])
}}};
JsonPerson.prototype.getDisplayName=function(){return this.getField("displayName")
};
JsonPerson.prototype.getAppData=function(B){var A=this.getField("appData");
return A&&A[B]
};;
var JsonMessageCollection=function(A){A=A||{};
opensocial.MessageCollection.call(this,A)
};
JsonMessageCollection.inherits(opensocial.MessageCollection);
JsonMessageCollection.prototype.toJsonObject=function(){return JsonMessageCollection.copyFields(this.fields_)
};
JsonMessageCollection.copyFields=function(A){var B={};
for(var C in A){B[C]=A[C]
}return B
};;
var JsonMessage=function(A,B){B=B||{};
opensocial.Message.call(this,A,B)
};
JsonMessage.inherits(opensocial.Message);
JsonMessage.prototype.toJsonObject=function(){return JsonMessage.copyFields(this.fields_)
};
JsonMessage.copyFields=function(A){var B={};
for(var C in A){B[C]=A[C]
}return B
};;
var gadgets=gadgets||{};
gadgets.rpctx=gadgets.rpctx||{};
gadgets.rpctx.wpm=function(){var A;
return{getCode:function(){return"wpm"
},isParentVerifiable:function(){return true
},init:function(B,C){A=C;
var D=function(E){B(gadgets.json.parse(E.data))
};
if(typeof window.addEventListener!="undefined"){window.addEventListener("message",D,false)
}else{if(typeof window.attachEvent!="undefined"){window.attachEvent("onmessage",D)
}}A("..",true);
return true
},setup:function(C,B){if(C===".."){gadgets.rpc.call(C,gadgets.rpc.ACK)
}return true
},call:function(C,F,E){var D=C===".."?window.parent:window.frames[C];
var B=gadgets.rpc.getOrigin(gadgets.rpc.getRelayUrl(C));
if(B){D.postMessage(gadgets.json.stringify(E),B)
}else{gadgets.error("No relay set (used as window.postMessage targetOrigin), cannot send cross-domain message")
}return true
}}
}();;
var gadgets=gadgets||{};
gadgets.rpctx=gadgets.rpctx||{};
gadgets.rpctx.frameElement=function(){var E="__g2c_rpc";
var B="__c2g_rpc";
var D;
var C;
function A(G,K,J){try{if(K!==".."){var F=window.frameElement;
if(typeof F[E]==="function"){if(typeof F[E][B]!=="function"){F[E][B]=function(L){D(gadgets.json.parse(L))
}
}F[E](gadgets.json.stringify(J));
return 
}}else{var I=document.getElementById(G);
if(typeof I[E]==="function"&&typeof I[E][B]==="function"){I[E][B](gadgets.json.stringify(J));
return 
}}}catch(H){}return true
}return{getCode:function(){return"fe"
},isParentVerifiable:function(){return false
},init:function(F,G){D=F;
C=G;
return true
},setup:function(J,F){if(J!==".."){try{var I=document.getElementById(J);
I[E]=function(K){D(gadgets.json.parse(K))
}
}catch(H){return false
}}if(J===".."){C("..",true);
var G=function(){window.setTimeout(function(){gadgets.rpc.call(J,gadgets.rpc.ACK)
},500)
};
gadgets.util.registerOnLoadHandler(G)
}return true
},call:function(F,H,G){A(F,H,G)
}}
}();;
var gadgets=gadgets||{};
gadgets.rpctx=gadgets.rpctx||{};
gadgets.rpctx.nix=function(){var C="GRPC____NIXVBS_wrapper";
var D="GRPC____NIXVBS_get_wrapper";
var F="GRPC____NIXVBS_handle_message";
var B="GRPC____NIXVBS_create_channel";
var A=10;
var J=500;
var I={};
var H;
var G=0;
function E(){var L=I[".."];
if(L){return 
}if(++G>A){gadgets.warn("Nix transport setup failed, falling back...");
H("..",false);
return 
}if(!L&&window.opener&&"GetAuthToken" in window.opener){L=window.opener;
if(L.GetAuthToken()==gadgets.rpc.getAuthToken("..")){var K=gadgets.rpc.getAuthToken("..");
L.CreateChannel(window[D]("..",K),K);
I[".."]=L;
window.opener=null;
H("..",true);
return 
}}window.setTimeout(function(){E()
},J)
}return{getCode:function(){return"nix"
},isParentVerifiable:function(){return false
},init:function(L,M){H=M;
if(typeof window[D]!=="unknown"){window[F]=function(O){window.setTimeout(function(){L(gadgets.json.parse(O))
},0)
};
window[B]=function(O,Q,P){if(gadgets.rpc.getAuthToken(O)===P){I[O]=Q;
H(O,true)
}};
var K="Class "+C+"\n Private m_Intended\nPrivate m_Auth\nPublic Sub SetIntendedName(name)\n If isEmpty(m_Intended) Then\nm_Intended = name\nEnd If\nEnd Sub\nPublic Sub SetAuth(auth)\n If isEmpty(m_Auth) Then\nm_Auth = auth\nEnd If\nEnd Sub\nPublic Sub SendMessage(data)\n "+F+"(data)\nEnd Sub\nPublic Function GetAuthToken()\n GetAuthToken = m_Auth\nEnd Function\nPublic Sub CreateChannel(channel, auth)\n Call "+B+"(m_Intended, channel, auth)\nEnd Sub\nEnd Class\nFunction "+D+"(name, auth)\nDim wrap\nSet wrap = New "+C+"\nwrap.SetIntendedName name\nwrap.SetAuth auth\nSet "+D+" = wrap\nEnd Function";
try{window.execScript(K,"vbscript")
}catch(N){return false
}}return true
},setup:function(O,K){if(O===".."){E();
return true
}try{var M=document.getElementById(O);
var N=window[D](O,K);
M.contentWindow.opener=N
}catch(L){return false
}return true
},call:function(K,N,M){try{if(I[K]){I[K].SendMessage(gadgets.json.stringify(M))
}}catch(L){return false
}return true
}}
}();;
var gadgets=gadgets||{};
gadgets.rpctx=gadgets.rpctx||{};
gadgets.rpctx.rmr=function(){var G=500;
var E=10;
var H={};
var B;
var I;
function K(P,N,O,M){var Q=function(){document.body.appendChild(P);
P.src="about:blank";
if(M){P.onload=function(){L(M)
}
}P.src=N+"#"+O
};
if(document.body){Q()
}else{gadgets.util.registerOnLoadHandler(function(){Q()
})
}}function C(O){if(typeof H[O]==="object"){return 
}var P=document.createElement("iframe");
var M=P.style;
M.position="absolute";
M.top="0px";
M.border="0";
M.opacity="0";
M.width="10px";
M.height="1px";
P.id="rmrtransport-"+O;
P.name=P.id;
var N=gadgets.rpc.getOrigin(gadgets.rpc.getRelayUrl(O))+"/robots.txt";
H[O]={frame:P,receiveWindow:null,relayUri:N,searchCounter:0,width:10,waiting:true,queue:[],sendId:0,recvId:0};
if(O!==".."){K(P,N,A(O))
}D(O)
}function D(N){var P=null;
H[N].searchCounter++;
try{if(N===".."){P=window.parent.frames["rmrtransport-"+gadgets.rpc.RPC_ID]
}else{P=window.frames[N].frames["rmrtransport-.."]
}}catch(O){}var M=false;
if(P){M=F(N,P)
}if(!M){if(H[N].searchCounter>E){return 
}window.setTimeout(function(){D(N)
},G)
}}function J(N,P,T,S){var O=null;
if(T!==".."){O=H[".."]
}else{O=H[N]
}if(O){if(P!==gadgets.rpc.ACK){O.queue.push(S)
}if(O.waiting||(O.queue.length===0&&!(P===gadgets.rpc.ACK&&S&&S.ackAlone===true))){return true
}if(O.queue.length>0){O.waiting=true
}var M=O.relayUri+"#"+A(N);
try{O.frame.contentWindow.location=M;
var Q=O.width==10?20:10;
O.frame.style.width=Q+"px";
O.width=Q
}catch(R){return false
}}return true
}function A(N){var O=H[N];
var M={id:O.sendId};
if(O){M.d=Array.prototype.slice.call(O.queue,0);
M.d.push({s:gadgets.rpc.ACK,id:O.recvId})
}return gadgets.json.stringify(M)
}function L(X){var U=H[X];
var Q=U.receiveWindow.location.hash.substring(1);
var Y=gadgets.json.parse(decodeURIComponent(Q))||{};
var N=Y.d||[];
var O=false;
var T=false;
var V=0;
var M=(U.recvId-Y.id);
for(var P=0;
P<N.length;
++P){var S=N[P];
if(S.s===gadgets.rpc.ACK){I(X,true);
if(U.waiting){T=true
}U.waiting=false;
var R=Math.max(0,S.id-U.sendId);
U.queue.splice(0,R);
U.sendId=Math.max(U.sendId,S.id||0);
continue
}O=true;
if(++V<=M){continue
}++U.recvId;
B(S)
}if(O||(T&&U.queue.length>0)){var W=(X==="..")?gadgets.rpc.RPC_ID:"..";
J(X,gadgets.rpc.ACK,W,{ackAlone:O})
}}function F(P,S){var O=H[P];
try{var N=false;
N="document" in S;
if(!N){return false
}N=typeof S.document=="object";
if(!N){return false
}var R=S.location.href;
if(R==="about:blank"){return false
}}catch(M){return false
}O.receiveWindow=S;
function Q(){L(P)
}if(typeof S.attachEvent==="undefined"){S.onresize=Q
}else{S.attachEvent("onresize",Q)
}if(P===".."){K(O.frame,O.relayUri,A(P),P)
}else{L(P)
}return true
}return{getCode:function(){return"rmr"
},isParentVerifiable:function(){return true
},init:function(M,N){B=M;
I=N;
return true
},setup:function(O,M){try{C(O)
}catch(N){gadgets.warn("Caught exception setting up RMR: "+N);
return false
}return true
},call:function(M,O,N){return J(M,N.s,O,N)
}}
}();;
var gadgets=gadgets||{};
gadgets.rpctx=gadgets.rpctx||{};
gadgets.rpctx.ifpc=function(){var E=[];
var D=0;
var C;
function B(H){var F=[];
for(var I=0,G=H.length;
I<G;
++I){F.push(encodeURIComponent(gadgets.json.stringify(H[I])))
}return F.join("&")
}function A(I){var G;
for(var F=E.length-1;
F>=0;
--F){var J=E[F];
try{if(J&&(J.recyclable||J.readyState==="complete")){J.parentNode.removeChild(J);
if(window.ActiveXObject){E[F]=J=null;
E.splice(F,1)
}else{J.recyclable=false;
G=J;
break
}}}catch(H){}}if(!G){G=document.createElement("iframe");
G.style.border=G.style.width=G.style.height="0px";
G.style.visibility="hidden";
G.style.position="absolute";
G.onload=function(){this.recyclable=true
};
E.push(G)
}G.src=I;
window.setTimeout(function(){document.body.appendChild(G)
},0)
}return{getCode:function(){return"ifpc"
},isParentVerifiable:function(){return true
},init:function(F,G){C=G;
C("..",true);
return true
},setup:function(G,F){C(G,true);
return true
},call:function(F,K,I){var J=gadgets.rpc.getRelayUrl(F);
++D;
if(!J){gadgets.warn("No relay file assigned for IFPC");
return 
}var H=null;
if(I.l){var G=I.a;
H=[J,"#",B([K,D,1,0,B([K,I.s,"","",K].concat(G))])].join("")
}else{H=[J,"#",F,"&",K,"@",D,"&1&0&",encodeURIComponent(gadgets.json.stringify(I))].join("")
}A(H);
return true
}}
}();;
var gadgets=gadgets||{};
gadgets.rpc=function(){var S="__cb";
var R="";
var G="__ack";
var Q=500;
var I=10;
var B={};
var C={};
var W={};
var J={};
var M=0;
var h={};
var V={};
var D={};
var e={};
var K={};
var U={};
var L=(window.top!==window.self);
var N=window.name;
var f=(function(){function i(j){return function(){gadgets.log("gadgets.rpc."+j+"("+gadgets.json.stringify(Array.prototype.slice.call(arguments))+"): call ignored. [caller: "+document.location+", isChild: "+L+"]")
}
}return{getCode:function(){return"noop"
},isParentVerifiable:function(){return true
},init:i("init"),setup:i("setup"),call:i("call")}
})();
if(gadgets.util){e=gadgets.util.getUrlParameters()
}var Z=(e.rpc_earlyq==="1");
function A(){return typeof window.postMessage==="function"?gadgets.rpctx.wpm:typeof window.postMessage==="object"?gadgets.rpctx.wpm:window.ActiveXObject?gadgets.rpctx.nix:navigator.userAgent.indexOf("WebKit")>0?gadgets.rpctx.rmr:navigator.product==="Gecko"?gadgets.rpctx.frameElement:gadgets.rpctx.ifpc
}function a(o,m){var k=b;
if(!m){k=f
}K[o]=k;
var j=U[o]||[];
for(var l=0;
l<j.length;
++l){var n=j[l];
n.t=X(o);
k.call(o,n.f,n)
}U[o]=[]
}function T(j){if(j&&typeof j.s==="string"&&typeof j.f==="string"&&j.a instanceof Array){if(J[j.f]){if(J[j.f]!==j.t){throw new Error("Invalid auth token. "+J[j.f]+" vs "+j.t)
}}if(j.s===G){window.setTimeout(function(){a(j.f,true)
},0);
return 
}if(j.c){j.callback=function(k){gadgets.rpc.call(j.f,S,null,j.c,k)
}
}var i=(B[j.s]||B[R]).apply(j,j.a);
if(j.c&&typeof i!=="undefined"){gadgets.rpc.call(j.f,S,null,j.c,i)
}}}function d(k){if(!k){return""
}k=k.toLowerCase();
if(k.indexOf("//")==0){k=window.location.protocol+k
}if(k.indexOf("://")==-1){k=window.location.protocol+"//"+k
}var l=k.substring(k.indexOf("://")+3);
var i=l.indexOf("/");
if(i!=-1){l=l.substring(0,i)
}var n=k.substring(0,k.indexOf("://"));
var m="";
var o=l.indexOf(":");
if(o!=-1){var j=l.substring(o+1);
l=l.substring(0,o);
if((n==="http"&&j!=="80")||(n==="https"&&j!=="443")){m=":"+j
}}return n+"://"+l+m
}var b=A();
B[R]=function(){gadgets.warn("Unknown RPC service: "+this.s)
};
B[S]=function(j,i){var k=h[j];
if(k){delete h[j];
k(i)
}};
function O(k,i){if(V[k]===true){return 
}if(typeof V[k]==="undefined"){V[k]=0
}var j=document.getElementById(k);
if(k===".."||j!=null){if(b.setup(k,i)===true){V[k]=true;
return 
}}if(V[k]!==true&&V[k]++<I){window.setTimeout(function(){O(k,i)
},Q)
}else{K[k]=f;
V[k]=true
}}function F(j,m){if(typeof D[j]==="undefined"){D[j]=false;
var l=gadgets.rpc.getRelayUrl(j);
if(d(l)!==d(window.location.href)){return false
}var k=null;
if(j===".."){k=window.parent
}else{k=window.frames[j]
}try{D[j]=k.gadgets.rpc.receiveSameDomain
}catch(i){gadgets.error("Same domain call failed: parent= incorrectly set.")
}}if(typeof D[j]==="function"){D[j](m);
return true
}return false
}function H(j,i,k){C[j]=i;
W[j]=!!k
}function X(i){return J[i]
}function E(i,j){j=j||"";
J[i]=String(j);
O(i,j)
}function P(i){function k(n){var p=n?n.rpc:{};
var m=p.parentRelayUrl;
if(m.substring(0,7)!=="http://"&&m.substring(0,8)!=="https://"&&m.substring(0,2)!=="//"){if(typeof e.parent==="string"&&e.parent!==""){if(m.substring(0,1)!=="/"){var l=e.parent.lastIndexOf("/");
m=e.parent.substring(0,l+1)+m
}else{m=d(e.parent)+m
}}}var o=!!p.useLegacyProtocol;
H("..",m,o);
if(o){b=gadgets.rpctx.ifpc;
b.init(T,a)
}E("..",i)
}var j={parentRelayUrl:gadgets.config.NonEmptyStringValidator};
gadgets.config.register("rpc",j,k)
}function Y(k,i){var j=i||e.parent;
if(j){H("..",j);
E("..",k)
}}function c(i,m,o){if(!gadgets.util){return 
}var l=document.getElementById(i);
if(!l){throw new Error("Cannot set up gadgets.rpc receiver with ID: "+i+", element not found.")
}var j=m||l.src;
H(i,j);
var n=gadgets.util.getUrlParameters(l.src);
var k=o||n.rpctoken;
E(i,k)
}function g(i,k,l){if(i===".."){var j=l||e.rpctoken||e.ifpctok||"";
if(gadgets.config){P(j)
}else{Y(j,k)
}}else{c(i,k,l)
}}if(L){g("..")
}return{register:function(j,i){if(j===S||j===G){throw new Error("Cannot overwrite callback/ack service")
}if(j===R){throw new Error("Cannot overwrite default service: use registerDefault")
}B[j]=i
},unregister:function(i){if(i===S||i===G){throw new Error("Cannot delete callback/ack service")
}if(i===R){throw new Error("Cannot delete default service: use unregisterDefault")
}delete B[i]
},registerDefault:function(i){B[R]=i
},unregisterDefault:function(){delete B[R]
},forceParentVerifiable:function(){if(!b.isParentVerifiable()){b=gadgets.rpctx.ifpc
}},call:function(i,j,o,m){i=i||"..";
var n="..";
if(i===".."){n=N
}++M;
if(o){h[M]=o
}var l={s:j,f:n,c:o?M:0,a:Array.prototype.slice.call(arguments,3),t:J[i],l:W[i]};
if(F(i,l)){return 
}var k=K[i]?K[i]:b;
if(!k){if(!U[i]){U[i]=[l]
}else{U[i].push(l)
}return 
}if(W[i]){k=gadgets.rpctx.ifpc
}if(k.call(i,n,l)===false){K[i]=f;
b.call(i,n,l)
}},getRelayUrl:function(j){var i=C[j];
if(i&&i.indexOf("//")==0){i=document.location.protocol+i
}return i
},setRelayUrl:H,setAuthToken:E,setupReceiver:g,getAuthToken:X,getRelayChannel:function(){return b.getCode()
},receive:function(i){if(i.length>4){T(gadgets.json.parse(decodeURIComponent(i[i.length-1])))
}},receiveSameDomain:function(i){i.a=Array.prototype.slice.call(i.a);
window.setTimeout(function(){T(i)
},0)
},getOrigin:d,init:function(){if(b.init(T,a)===false){b=f
}},ACK:G,RPC_ID:N}
}();
gadgets.rpc.init();;
var JsonRpcContainer=function(C){opensocial.Container.call(this);
var H=C.path;
this.path_=H.replace("%host%",document.location.host);
var F=C.invalidatePath;
this.invalidatePath_=F.replace("%host%",document.location.host);
var G=C.supportedFields;
var E={};
for(var B in G){if(G.hasOwnProperty(B)){E[B]={};
for(var D=0;
D<G[B].length;
D++){var A=G[B][D];
E[B][A]=true
}}}this.environment_=new opensocial.Environment(C.domain,E);
this.securityToken_=shindig.auth.getSecurityToken();
gadgets.rpc.register("shindig.requestShareApp_callback",JsonRpcContainer.requestShareAppCallback_)
};
var JsonRpcRequestItem=function(B,A){this.rpc=B;
this.processData=A||function(C){return C
};
this.processResponse=function(C,F,E,D){var G=E?JsonRpcContainer.translateHttpError(E.code):null;
return new opensocial.ResponseItem(C,E?null:this.processData(F),G,D)
}
};
(function(){var A={};
JsonRpcContainer.inherits(opensocial.Container);
JsonRpcContainer.prototype.getEnvironment=function(){return this.environment_
};
JsonRpcContainer.prototype.requestShareApp=function(F,H,C,D){var E="cId_"+Math.random();
A[E]=C;
var B=gadgets.util.unescapeString(H.getField(opensocial.Message.Field.BODY));
if(!B||B.length===0){var G=gadgets.util.unescapeString(H.getField(opensocial.Message.Field.BODY_ID));
B=gadgets.Prefs.getMsg(G)
}gadgets.rpc.call("..","shindig.requestShareApp",null,E,F,B)
};
JsonRpcContainer.requestShareAppCallback_=function(F,G,C,E){callback=A[F];
if(callback){A[F]=null;
var D=null;
if(E){D={recipientIds:E}
}var B=new opensocial.ResponseItem(null,D,C);
callback(B)
}};
JsonRpcContainer.prototype.requestCreateActivity=function(E,C,B){B=B||function(){};
var D=opensocial.newDataRequest();
var F=opensocial.newIdSpec({userId:"VIEWER"});
D.add(this.newCreateActivityRequest(F,E),"key");
D.send(function(G){B(G.get("key"))
})
};
JsonRpcContainer.prototype.requestData=function(G,K){K=K||function(){};
var E=G.getRequestObjects();
var I=E.length;
if(I===0){window.setTimeout(function(){K(new opensocial.DataResponse({},true))
},0);
return 
}var L=new Array(I);
for(var F=0;
F<I;
F++){var J=E[F];
L[F]=J.request.rpc;
if(J.key){L[F].id=J.key
}}var C=function(X){if(X.errors[0]){JsonRpcContainer.generateErrorResponse(X,E,K);
return 
}X=X.data;
var N=false;
var W={};
for(var R=0;
R<X.length;
R++){X[X[R].id]=X[R]
}for(var O=0;
O<E.length;
O++){var Q=E[O];
var P=X[O];
if(Q.key&&P.id!==Q.key){throw"Request key("+Q.key+") and response id("+P.id+") do not match"
}var M=P.data;
var U=P.error;
var T="";
if(U){T=U.message
}var S=Q.request.processResponse(Q.request,M,U,T);
N=N||S.hadError();
if(Q.key){W[Q.key]=S
}}var V=new opensocial.DataResponse(W,N);
K(V)
};
var H={CONTENT_TYPE:"JSON",METHOD:"POST",AUTHORIZATION:"SIGNED",POST_DATA:gadgets.json.stringify(L)};
var B=[this.path_];
var D=shindig.auth.getSecurityToken();
if(D){B.push("?st=",encodeURIComponent(D))
}this.sendRequest(B.join(""),C,H,"application/json")
};
JsonRpcContainer.prototype.sendRequest=function(B,E,C,D){gadgets.io.makeNonProxiedRequest(B,E,C,D)
};
JsonRpcContainer.generateErrorResponse=function(B,E,G){var C=JsonRpcContainer.translateHttpError(B.rc||B.data.error)||opensocial.ResponseItem.Error.INTERNAL_ERROR;
var F={};
for(var D=0;
D<E.length;
D++){F[E[D].key]=new opensocial.ResponseItem(E[D].request,null,C)
}G(new opensocial.DataResponse(F,true))
};
JsonRpcContainer.translateHttpError=function(B){if(B==501){return opensocial.ResponseItem.Error.NOT_IMPLEMENTED
}else{if(B==401){return opensocial.ResponseItem.Error.UNAUTHORIZED
}else{if(B==403){return opensocial.ResponseItem.Error.FORBIDDEN
}else{if(B==400){return opensocial.ResponseItem.Error.BAD_REQUEST
}else{if(B==500){return opensocial.ResponseItem.Error.INTERNAL_ERROR
}else{if(B==404){return opensocial.ResponseItem.Error.BAD_REQUEST
}else{if(B==417){return opensocial.ResponseItem.Error.LIMIT_EXCEEDED
}}}}}}}};
JsonRpcContainer.prototype.makeIdSpec=function(B){return opensocial.newIdSpec({userId:B})
};
JsonRpcContainer.prototype.translateIdSpec=function(B){var E=B.getField("userId");
var D=B.getField("groupId");
if(!opensocial.Container.isArray(E)){E=[E]
}for(var C=0;
C<E.length;
C++){if(E[C]==="OWNER"){E[C]="@owner"
}else{if(E[C]==="VIEWER"){E[C]="@viewer"
}}}if(D==="FRIENDS"){D="@friends"
}else{if(D==="SELF"||!D){D="@self"
}}return{userId:E,groupId:D}
};
JsonRpcContainer.prototype.newFetchPersonRequest=function(E,D){var B=this.newFetchPeopleRequest(this.makeIdSpec(E),D);
var C=this;
return new JsonRpcRequestItem(B.rpc,function(F){return C.createPersonFromJson(F,D)
})
};
JsonRpcContainer.prototype.newFetchPeopleRequest=function(B,D){var E={method:"people.get"};
E.params=this.translateIdSpec(B);
FieldTranslations.addAppDataAsProfileFields(D);
FieldTranslations.translateStandardArguments(D,E.params);
FieldTranslations.translateNetworkDistance(B,E.params);
if(D.profileDetail){FieldTranslations.translateJsPersonFieldsToServerFields(D.profileDetail);
E.params.fields=D.profileDetail
}var C=this;
return new JsonRpcRequestItem(E,function(I){var H;
if(I.list){H=I.list
}else{H=[I]
}var G=[];
for(var F=0;
F<H.length;
F++){G.push(C.createPersonFromJson(H[F],D))
}return new opensocial.Collection(G,I.startIndex,I.totalResults)
})
};
JsonRpcContainer.prototype.createPersonFromJson=function(B,C){FieldTranslations.translateServerPersonToJsPerson(B,C);
return new JsonPerson(B)
};
JsonRpcContainer.prototype.getFieldsList=function(B){if(this.hasNoKeys(B)||this.isWildcardKey(B[0])){return[]
}else{return B
}};
JsonRpcContainer.prototype.hasNoKeys=function(B){return !B||B.length===0
};
JsonRpcContainer.prototype.isWildcardKey=function(B){return B==="*"
};
JsonRpcContainer.prototype.newFetchPersonAppDataRequest=function(B,D,C){var E={method:"appdata.get"};
E.params=this.translateIdSpec(B);
E.params.appId="@app";
E.params.fields=this.getFieldsList(D);
FieldTranslations.translateNetworkDistance(B,E.params);
return new JsonRpcRequestItem(E,function(F){return opensocial.Container.escape(F,C,true)
})
};
JsonRpcContainer.prototype.newUpdatePersonAppDataRequest=function(B,C){var D={method:"appdata.update"};
D.params={userId:["@viewer"],groupId:"@self"};
D.params.appId="@app";
D.params.data={};
D.params.data[B]=C;
D.params.fields=B;
return new JsonRpcRequestItem(D)
};
JsonRpcContainer.prototype.newRemovePersonAppDataRequest=function(B){var C={method:"appdata.delete"};
C.params={userId:["@viewer"],groupId:"@self"};
C.params.appId="@app";
C.params.fields=this.getFieldsList(B);
return new JsonRpcRequestItem(C)
};
JsonRpcContainer.prototype.newFetchActivitiesRequest=function(B,C){var D={method:"activities.get"};
D.params=this.translateIdSpec(B);
D.params.appId="@app";
FieldTranslations.translateStandardArguments(C,D.params);
FieldTranslations.translateNetworkDistance(B,D.params);
return new JsonRpcRequestItem(D,function(F){F=F.list;
var G=[];
for(var E=0;
E<F.length;
E++){G.push(new JsonActivity(F[E]))
}return new opensocial.Collection(G)
})
};
JsonRpcContainer.prototype.newActivity=function(B){return new JsonActivity(B,true)
};
JsonRpcContainer.prototype.newMediaItem=function(D,B,C){C=C||{};
C.mimeType=D;
C.url=B;
return new JsonMediaItem(C)
};
JsonRpcContainer.prototype.newCreateActivityRequest=function(B,C){var D={method:"activities.create"};
D.params=this.translateIdSpec(B);
D.params.appId="@app";
FieldTranslations.translateNetworkDistance(B,D.params);
D.params.activity=C.toJsonObject();
return new JsonRpcRequestItem(D)
};
JsonRpcContainer.prototype.invalidateCache=function(){var F={method:"cache.invalidate"};
var C={invalidationKeys:["@viewer"]};
F.params=C;
var E={CONTENT_TYPE:"JSON",METHOD:"POST",AUTHORIZATION:"SIGNED",POST_DATA:gadgets.json.stringify(F)};
var B=[this.invalidatePath_];
var D=shindig.auth.getSecurityToken();
if(D){B.push("?st=",encodeURIComponent(D))
}this.sendRequest(B.join(""),null,E,"application/json")
}
})();
JsonRpcContainer.prototype.newMessage=function(A,B){return new JsonMessage(A,B)
};
JsonRpcContainer.prototype.newMessageCollection=function(A){return new JsonMessageCollection(A)
};
JsonRpcContainer.prototype.newFetchMessageCollectionsRequest=function(A,B){var C={method:"messages.get"};
C.params=this.translateIdSpec(A);
return new JsonRpcRequestItem(C,function(E){E=E.list;
var F=[];
for(var D=0;
D<E.length;
D++){F.push(new JsonMessageCollection(E[D]))
}return new opensocial.Collection(F)
})
};
JsonRpcContainer.prototype.newFetchMessagesRequest=function(A,C,B){var D={method:"messages.get"};
D.params=this.translateIdSpec(A);
D.params.msgCollId=C;
return new JsonRpcRequestItem(D,function(G){G=G.list;
var F=[];
for(var E=0;
E<G.length;
E++){F.push(new JsonMessage(G[E]))
}return new opensocial.Collection(F)
})
};;
gadgets.io.originalMakeRequest=gadgets.io.makeRequest;
gadgets.io.makeRequest=function(a,f,d){if(!d){d=[]
}var e=gadgets.io;
var c=e.RequestParameters;
var b=d[c.AUTHORIZATION];
d[c.AUTHORIZATION]=b||e.AuthorizationType.SIGNED;
e.originalMakeRequest(a,function(g){if(g&&g.rc&&g.rc>=400&&g.errors&&g.errors[0]===undefined){g.errors[0]=g.rc+""
}f(g)
},d)
};
opensocial.requestSendMessage=function(a,d,b,c){opensocial.Container.get().requestSendMessage(a,d,b,c)
};
opensocial.Container.prototype.requestSendMessage=function(e,i,f,k){var q="theRecipients";
var l="recipientDestOwnerIdKey";
var g="viewerDestOwnerIdKey";
var c=undefined;
var o=undefined;
var p=undefined;
var s=undefined;
var j=i.getField(opensocial.Message.Field.BODY);
var d=i.getField(opensocial.Message.Field.TITLE);
var a=opensocial.newDataRequest();
var m=false;
var n=false;
if(e&&(e.setField||(e instanceof Array))){if((e instanceof Array)||(!e.getField)){e=new opensocial.newIdSpec({userId:e})
}else{e.setField(opensocial.IdSpec.Field.GROUP_ID,"")
}a.add(a.newFetchPeopleRequest(e),q);
m=true
}if(k&&k[opensocial.NavigationParameters.DestinationType.RECIPIENT_DESTINATION]){var r=k[opensocial.NavigationParameters.DestinationType.RECIPIENT_DESTINATION];
if(r.getField("owner")){if(r.getField("owner").getField&&r.getField("owner").getField("userId")){if(r.getField("owner").getField("userId")=="0"){n=true
}else{a.add(a.newFetchPeopleRequest(r.getField("owner")),l);
m=true
}}else{if(r.getField("owner")=="0"){n=true
}else{a.add(a.newFetchPeopleRequest(new opensocial.IdSpec({userId:r.getField("owner")})),l);
m=true
}}}if(r.getField("view")){c=r.getField("view").getName()
}if(r.getField("parameters")){o=r.getField("parameters")
}}var b="home";
if(gadgets.views){b=gadgets.views.getCurrentView().getName()
}var h={};
if(gadgets.util.getUrlParameters()["view-params"]&&gadgets.util.getUrlParameters()["view-params"]!={}){h=gadgets.json.parse(decodeURIComponent(gadgets.util.getUrlParameters()["view-params"]))
}var t=false;
if(k&&k[opensocial.NavigationParameters.DestinationType.VIEWER_DESTINATION]){var r=k[opensocial.NavigationParameters.DestinationType.VIEWER_DESTINATION];
if(r.getField("owner")&&r.getField("owner").getField&&r.getField("owner").getField("userId")&&r.getField("owner").getField("userId")!="0"){a.add(a.newFetchPeopleRequest(r.getField("owner")),g);
m=true
}else{if(r.getField("owner")&&r.getField("owner")!="0"){a.add(a.newFetchPeopleRequest(opensocial.newIdSpec({userId:r.getField("owner")})),g);
m=true
}else{a.add(a.newFetchPeopleRequest(opensocial.newIdSpec({userId:"OWNER"})),g);
m=true
}}if(r.getField("view")){t=true;
p=r.getField("view").getName()
}else{p=b
}if(r.getField("parameters")){s=r.getField("parameters")
}if(!t){p=b;
s=h
}}if(m){a.send(_LI_requestSendMessageHelper(d,j,q,l,c,o,g,p,s,n))
}else{gadgets.rpc.call(null,"requestSendMessage",null,"",j,d,c,undefined,o,p,undefined,s,n)
}};
JsonRpcContainer.prototype.requestShareApp=function(a,e,c,d){var b=opensocial.newMessage();
b.setField(opensocial.Message.Field.BODY,"I would like you to try this Application on LinkedIn.");
b.setField(opensocial.Message.Field.TITLE,"Try this Application");
if(!e){e=b
}opensocial.Container.get().requestSendMessage(a,e,c,d)
};
opensocial.requestCreateActivity=function(e,b,a){if(!e||!e.getField(opensocial.Activity.Field.BODY)){if(a){a(new opensocial.ResponseItem(null,null,opensocial.ResponseItem.Error.BAD_REQUEST,"Activity BODY is mandatory."))
}return
}if(e.getField(opensocial.Activity.Field.URL)==""||e.getField(opensocial.Activity.Field.URL)==undefined){var d=gadgets.util.getUrlParameters()["signedUrlToCanvasView"];
e.setField(opensocial.Activity.Field.URL,d)
}a=a||{};
var c=opensocial.newDataRequest();
c.add(opensocial.Container.get().newCreateActivityRequest(opensocial.newIdSpec({userId:"VIEWER"}),e),"key");
c.send(function(f){a(f.get("key"))
})
};
opensocial.Container.prototype.hasPermission=function(a){if(a!=opensocial.Permission.VIEWER){return false
}var b=gadgets.util.getUrlParameters()["viewerAccess"];
return b=="true"
};
opensocial.Container.prototype.requestPermission=function(c,d,b){if((!c)||(!(c instanceof Array))||(!c[0])||(c[0]=="")||(c[0]!=opensocial.Permission.VIEWER)){var a="Unknown error";
if(!c){message="permissions is undefined"
}else{if(!(c instanceof Array)){message="permissions must be an array of opensocial.Permission"
}else{if(!c[0]){message="permissions[0] is undefined"
}else{if(c[0]==""){message="permissions[0] is empty"
}else{if(c[0]!=opensocial.Permission.VIEWER){message="Only request permission to access to "+opensocial.Permission.VIEWER+" is allowed"
}}}}}b(new opensocial.ResponseItem(null,null,opensocial.ResponseItem.Error.BAD_REQUEST,message))
}if(!opensocial.Container.get().hasPermission([opensocial.Permission.VIEWER])){gadgets.rpc.call(null,"requestPermission",null,c[0],d,b)
}else{if(b){b([opensocial.Permission.VIEWER])
}}};
if(gadgets.views){gadgets.views.requestNavigateTo=function(b,f,c){var e=opensocial.newDataRequest();
var a=false;
var d="viewerDestOwnerIdKey";
if(c=="viewer"){e.add(e.newFetchPeopleRequest(new opensocial.newIdSpec({userId:"viewer"})),d);
a=true
}if(a){e.send(_LI_requestNavigateToHelper(b,f,c,d))
}else{gadgets.rpc.call(null,"requestNavigateTo",null,b.getName(),f,c)
}}
}opensocial.DataRequest.prototype.addDefaultProfileFields_orig=opensocial.DataRequest.prototype.addDefaultProfileFields;
opensocial.DataRequest.prototype.addDefaultProfileFields=function(b){opensocial.DataRequest.prototype.addDefaultProfileFields_orig(b);
var a=opensocial.DataRequest.PeopleRequestFields;
var c=b[a.PROFILE_DETAILS]||[];
b[a.PROFILE_DETAILS]=b[a.PROFILE_DETAILS].concat([opensocial.Person.Field.PROFILE_URL])
};;
_LI_requestSendMessageHelper=function(i,g,b,d,j,h,e,f,a,l){this._subject=i;
this._body=g;
this._recipientsKey=b;
this._recipientDestOwnerIdKey=d;
this._recipientDestViewName=j;
this._recipientDestParams=h;
this._viewerDestOwnerIdKey=e;
this._viewerDestViewName=f;
this._viewerDestParams=a;
this._useImplicitOwner=l;
var c=function k(m){var o=new Array();
if(m.get(_recipientsKey)&&m.get(_recipientsKey).getData()){var n=m.get(_recipientsKey).getData();
if(n.each){n.each(function(p){o.push(p.getField(opensocial.Person.Field.ID))
})
}else{o.push(person.getField(opensocial.Person.Field.ID))
}}if(m.get(_recipientDestOwnerIdKey)&&m.get(_recipientDestOwnerIdKey).getData()){this._recipientDestOwnerId=m.get(_recipientDestOwnerIdKey).getData().asArray()[0].getField(opensocial.Person.Field.ID)
}if(m.get(_viewerDestOwnerIdKey)&&m.get(_viewerDestOwnerIdKey).getData()){this._viewerDestOwnerId=m.get(_viewerDestOwnerIdKey).getData().asArray()[0].getField(opensocial.Person.Field.ID)
}gadgets.rpc.call(null,"requestSendMessage",null,o.join(","),this._body,this._subject,this._recipientDestViewName,this._recipientDestOwnerId,this._recipientDestParams,this._viewerDestViewName,this._viewerDestOwnerId,this._viewerDestParams,this._useImplicitOwner)
};
return c
};
_LI_requestNavigateToHelper=function(a,e,c,d){this._view=a;
this._opt_params=e;
this._opt_ownerId=c;
this._viewerIdKey=d;
var f=function b(g){if(g.get(this._viewerIdKey)&&g.get(this._viewerIdKey).getData()){this._opt_ownerId=g.get(this._viewerIdKey).getData().asArray()[0].getField(opensocial.Person.Field.ID)
}gadgets.rpc.call(null,"requestNavigateTo",null,this._view.getName(),this._opt_params,this._opt_ownerId)
};
return f
};;

      var requiredConfig = {
        "path": gadgets.config.NonEmptyStringValidator,
        "domain": gadgets.config.NonEmptyStringValidator,
        "enableCaja": gadgets.config.BooleanValidator,
        "supportedFields": gadgets.config.ExistsValidator
      };

      gadgets.config.register("opensocial-0.9", requiredConfig,
        function(config) {
          ShindigContainer = function() {
            JsonRpcContainer.call(this, config["opensocial-0.9"]);
          };
          ShindigContainer.inherits(JsonRpcContainer);

          opensocial.Container.setContainer(new ShindigContainer());
      });
      gadgets.config.register("opensocial-0.8", requiredConfig,
        function(config) {
          ShindigContainer = function() {
            JsonRpcContainer.call(this, config["opensocial-0.9"]);
          };
          ShindigContainer.inherits(JsonRpcContainer);

          opensocial.Container.setContainer(new ShindigContainer());
      });

    ;
var gadgets=gadgets||{};
gadgets.window=gadgets.window||{};
(function(){gadgets.window.getViewportDimensions=function(){var A,B;
if(self.innerHeight){A=self.innerWidth;
B=self.innerHeight
}else{if(document.documentElement&&document.documentElement.clientHeight){A=document.documentElement.clientWidth;
B=document.documentElement.clientHeight
}else{if(document.body){A=document.body.clientWidth;
B=document.body.clientHeight
}else{A=0;
B=0
}}}return{width:A,height:B}
}
})();;
var gadgets=gadgets||{};
gadgets.window=gadgets.window||{};
(function(){var C;
function A(F,D){var E=window.getComputedStyle(F,"");
var G=E.getPropertyValue(D);
G.match(/^([0-9]+)/);
return parseInt(RegExp.$1,10)
}function B(){var E=0;
var D=[document.body];
while(D.length>0){var I=D.shift();
var H=I.childNodes;
for(var G=0;
G<H.length;
G++){var J=H[G];
if(typeof J.offsetTop!=="undefined"&&typeof J.scrollHeight!=="undefined"){var F=J.offsetTop+J.scrollHeight+A(J,"margin-bottom");
E=Math.max(E,F)
}D.push(J)
}}return E+A(document.body,"border-bottom")+A(document.body,"margin-bottom")+A(document.body,"padding-bottom")
}gadgets.window.adjustHeight=function(I){var F=parseInt(I,10);
var E=false;
if(isNaN(F)){E=true;
var K=gadgets.window.getViewportDimensions().height;
var D=document.body;
var J=document.documentElement;
if(document.compatMode==="CSS1Compat"&&J.scrollHeight){F=J.scrollHeight!==K?J.scrollHeight:J.offsetHeight
}else{if(navigator.userAgent.indexOf("AppleWebKit")>=0){F=B()
}else{if(D&&J){var G=J.scrollHeight;
var H=J.offsetHeight;
if(J.clientHeight!==H){G=D.scrollHeight;
H=D.offsetHeight
}if(G>K){F=G>H?G:H
}else{F=G<H?G:H
}}}}}if(F!==C&&!isNaN(F)&&!(E&&F===0)){C=F;
gadgets.rpc.call(null,"resize_iframe",null,F)
}}
}());
var _IG_AdjustIFrameHeight=gadgets.window.adjustHeight;;
var tamings___=tamings___||[];
tamings___.push(function(A){caja___.whitelistFuncs([[gadgets.window,"adjustHeight"],[gadgets.window,"getViewportDimensions"],])
});;
var gadgets=gadgets||{};
gadgets.window=gadgets.window||{};
gadgets.window.setTitle=function(A){gadgets.rpc.call(null,"set_title",null,A)
};
var _IG_SetTitle=gadgets.window.setTitle;;
var gadgets=gadgets||{};
gadgets.views=function(){var f=null;
var b={};
var d={};
function a(i){if(!i){i=window.event
}var h;
if(i.target){h=i.target
}else{if(i.srcElement){h=i.srcElement
}}if(h.nodeType===3){h=h.parentNode
}if(h.nodeName.toLowerCase()==="a"){var g=h.getAttribute("href");
if(g&&g[0]!=="#"&&g.indexOf("://")===-1){gadgets.views.requestNavigateTo(f,g);
if(i.stopPropagation){i.stopPropagation()
}if(i.preventDefault){i.preventDefault()
}i.returnValue=false;
i.cancelBubble=true;
return false
}}return false
}function c(k){var j=k.views||{};
for(var n in j){if(j.hasOwnProperty(n)){if(n!="rewriteLinks"){var o=j[n];
if(!o){continue
}b[n]=new gadgets.views.View(n,o.isOnlyVisible);
var g=o.aliases||[];
for(var m=0,l;
l=g[m];
++m){b[l]=new gadgets.views.View(n,o.isOnlyVisible)
}}}}var h=gadgets.util.getUrlParameters();
if(h["view-params"]){d=gadgets.json.parse(h["view-params"])||d
}f=b[h.view]||b["default"];
if(j.rewriteLinks){if(document.attachEvent){document.attachEvent("onclick",a)
}else{document.addEventListener("click",a,false)
}}}gadgets.config.register("views",null,c);
return{bind:function(I,G){var m=n("owner",[]);
var z=this.getCurrentView().getName().split(".")[0];
if(m&&(m=="0")&&z=="canvas"){var x="{-join|&|params}";
var r=gadgets.util.getUrlParameters()["urlToCanvasView"];
I=r+"&_ownerId=0&"+x
}if(typeof I!="string"){throw new Error("Invalid urlTemplate")
}if(!G){G={}
}if(typeof G!="object"){throw new Error("Invalid environment")
}var F=/^([a-zA-Z0-9][a-zA-Z0-9_\.\-]*)(=([a-zA-Z0-9\-\._~]|(%[0-9a-fA-F]{2}))*)?$/,K=new RegExp("\\{([^}]*)\\}","g"),H=/^-([a-zA-Z]+)\|([^|]*)\|(.+)$/,s=[],C=0,p,o,l,A,q,j,w,E;
function n(v,k){if(!G||G=={}){return""
}return G.hasOwnProperty(v)?G[v]:k
}function i(k){if(!(o=k.match(F))){throw new Error("Invalid variable : "+k)
}}function J(N,k,M){var v,L=N.split(",");
for(v=0;
v<L.length;
++v){i(L[v]);
if(M(k,n(o[1]),o[1])){break
}}return k
}while(p=K.exec(I)){s.push(I.substring(C,p.index));
C=K.lastIndex;
if(o=p[1].match(F)){l=o[1];
A=o[2]?o[2].substr(1):"";
s.push(n(l,A))
}else{if(o=p[1].match(H)){q=o[1];
j=o[2];
w=o[3];
E=0;
switch(q){case"neg":E=1;
case"opt":if(J(w,{flag:E},function(L,k){if(typeof k!="undefined"&&(typeof k!="object"||k.length)){L.flag=!L.flag;
return 1
}}).flag){s.push(j)
}break;
case"join":var y=n("params",[]);
var g=0;
var B="";
var t="";
var u=false;
for(e in y){g++;
if(e!=="0"&&g==1){u=true
}if(u){B=e;
t=y[e];
s.push('"'+B+'":"'+t+'",')
}else{if(Math.round(g/2)!=(g/2)){B=y[e]
}else{t=y[e];
s.push('"'+B+'":"'+t+'",')
}}}var D=s[0];
s[0]="";
var h=s.join("");
s=new Array();
s.push(D+"appParams="+encodeURIComponent("{"+h.substring(0,h.length-1)+"}"));
break;
case"list":i(w);
value=n(o[1]);
if(typeof value==="object"&&typeof value.join==="function"){s.push(value.join(j))
}break;
case"prefix":E=1;
case"suffix":i(w);
value=n(o[1],o[2]&&o[2].substr(1));
if(typeof value==="string"){s.push(E?j+value:value+j)
}else{if(typeof value==="object"&&typeof value.join==="function"){s.push(E?j+value.join(j):value.join(j)+j)
}}break;
default:throw new Error("Invalid operator : "+q)
}}else{throw new Error("Invalid syntax : "+p[0])
}}}s.push(I.substr(C));
return s.join("")
},requestNavigateTo:function(g,i,h){if(typeof g!=="string"){g=g.getName()
}gadgets.rpc.call(null,"requestNavigateTo",null,g,i,h)
},getCurrentView:function(){return f
},getSupportedViews:function(){return b
},getParams:function(){return d
}}
}();
gadgets.views.View=function(a,b){this.name_=a;
this.isOnlyVisible_=!!b
};
gadgets.views.View.prototype.getName=function(){return this.name_
};
gadgets.views.View.prototype.getUrlTemplate=function(){var b="{-join|&|params}";
var h=gadgets.util.getUrlParameters();
var f=h["signedUrlToCanvasView"];
var d=h["baseLeoNonSecureURL"];
var c=h["ownerProfileUrl"];
var a=this.name_.split(".")[0];
if(a=="canvas"){var g=this.name_==a?"":"&view="+this.name_;
return f+g+"&"+b
}else{if(a=="home"){return d+"home"
}else{if(a=="profile"){return c
}}}};
gadgets.views.View.prototype.bind=function(a){return gadgets.views.bind(this.getUrlTemplate(),a)
};
gadgets.views.View.prototype.isOnlyVisibleGadget=function(){return this.isOnlyVisible_
};
gadgets.views.ViewType=gadgets.util.makeEnum(["CANVAS","HOME","PREVIEW","PROFILE","FULL_PAGE","DASHBOARD","POPUP"]);;
