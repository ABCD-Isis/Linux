<?xml version="1.0"?><!--
<%--
/*
 * Copyright 2004-2005 Kalio.Net - Barzilai Spinak - Ciro Mondueri
 * All Rights Reserved
 *
 */
--%>
<%@ tag body-content="scriptless" %>
<%@ attribute name="dateStyle" %>
<%@ attribute name="pattern" %>
<%@ attribute name="var" required="true" rtexprvalue="false" %>
<%@ variable  name-from-attribute="var"  alias="varout" scope="AT_END" %>

<%@ tag import="java.util.Date" %>
<%@ tag import="java.util.GregorianCalendar" %>
<%@ tag import="java.text.SimpleDateFormat" %>
<%@ tag import="java.text.ParseException" %>

<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<%@ taglib prefix="fmt" uri="http://java.sun.com/jsp/jstl/fmt" %>
<%@ taglib prefix="fn" uri="http://java.sun.com/jsp/jstl/functions" %>
-->

<fmt:setLocale value="${sessionScope.userLocale}"/>
<fmt:setBundle basename="ewi18n.core.gui" var="guiBundle" scope="page"/>

<jsp:doBody var="timestamp"/>
<%

String timestamp = (String)jspContext.getAttribute("timestamp");
String ts = (timestamp != null) ? timestamp.trim() : timestamp; 

String thePattern = "yyyyMMMddHHmmss";
if ( (pattern != null) && (pattern.trim().length() > 0) ) {
    thePattern = pattern;
} else if ( ts.length() == 8) {
    thePattern = "yyyyMMdd";
} else if ( ts.length() == 14) {
    thePattern = "yyyyMMddHHmmss";
}

try {
    SimpleDateFormat sdf = new SimpleDateFormat(thePattern);
    Date d = sdf.parse(ts);
    getJspContext().setAttribute("varout", d);
} catch (ParseException ex) {
    getJspContext().setAttribute("varout", null);
}
%>
