<%--
/*
 * Copyright 2004-2005 Kalio.Net - Barzilai Spinak - Ciro Mondueri
 * All Rights Reserved
 *
 */
--%>
<%@ tag body-content="empty" %>
<%@ tag import="net.kalio.auth.*" %>
<%@ attribute name="id" required="true" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<%@ taglib prefix="fmt" uri="http://java.sun.com/jsp/jstl/fmt" %>
<%@ taglib prefix="kfn"   uri="http://kalio.net/jsp/el-func-1.0" %>
<%-- procesar el tema y devolver el html de ok o error --%>

<%
Auth.setAuthPath( System.getProperty("empweb.home", "/") +
                  application.getInitParameter("net.kalio.auth.location"));
String result = Auth.newUser( id );
getJspContext().setAttribute("resultkey", result);

if ("".equals(result)) {
%>
      <c:redirect url="edit_operator.jsp?id=${kfn:urlenc(id)}"/>
<%
     } else {
%>
<p><fmt:message key="new_operator_failed"/>: <strong><fmt:message key="${resultkey}"/></strong></p>
<%
     }
%>
