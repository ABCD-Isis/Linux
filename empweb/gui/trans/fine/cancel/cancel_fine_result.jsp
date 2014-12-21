<?xml version="1.0" ?><!--
<%@ page contentType="text/html; charset=UTF-8" %>
<%@ taglib prefix="fmt" uri="http://java.sun.com/jsp/jstl/fmt" %>
<%@ taglib prefix="trans" tagdir="/WEB-INF/tags/trans" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<%@ taglib prefix="fn" uri="http://java.sun.com/jsp/jstl/functions" %>
<%@ taglib prefix="jxp" tagdir="/WEB-INF/tags/commons/jxp" %>
<%@ taglib prefix="dsp" tagdir="/WEB-INF/tags/display" %>
<%--
/*
 * Copyright 2004-2005 Kalio.Net - Barzilai Spinak - Ciro Mondueri
 * All Rights Reserved
 *
 */
--%>
-->

<div class="middle homepage">

  <c:choose>

    <%-- when a field is missing, reject with available parameters --%>
    <c:when test="${( (empty fn:trim(param.fine_id)) )}">
      <c:redirect url="index.jsp">
        <c:if test="${not empty fn:trim(param.fine_id) }">
          <c:param name="fine_id" value="${param.fine_id}"/>
        </c:if>
        <c:if test="${not empty fn:trim(param.submit) }">
          <c:param name="submit" value="${param.submit}"/>
        </c:if>
      </c:redirect>
    </c:when>


    <%-- show cancel_fine result --%>
    <c:otherwise>
      <h1><fmt:message key="cancel_fine"/></h1>
      <h2><fmt:message key="cancel_fine_result"/></h2>

      <trans:transResult>
        <trans:cancelFineSingle
          fineId="${fn:trim(param.fine_id)}"
          operatorId="${sessionScope.user}"
          obs="${fn:trim(param.obs)}" />
      </trans:transResult>
    </c:otherwise>

  </c:choose>

  <dsp:transactionResultFooter depth="3"/>

</div>
