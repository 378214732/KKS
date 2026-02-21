<?php
/*
 +----------------------------------------------------------------------
 | KASUSHOU [ 虚拟电商解决方案提供商，助力数字电商行业创业者 ]
 +----------------------------------------------------------------------
 | Copyright (c) 2016~2026 https://www.kasushou.com All rights reserved.
 +----------------------------------------------------------------------
 | Licensed KASUSHOU并不是自由软件，未经许可不能去掉KASUSHOU相关版权
 +----------------------------------------------------------------------
 | Author: KASUSHOU Team <admin@kasushou.com>
 +----------------------------------------------------------------------
*/

if(!defined('X393CBE779E6480A668B516D3C9CE389D'))define('X393CBE779E6480A668B516D3C9CE389D',__FILE__);eval(base64_decode('bmFtZXNwYWNlIGFwcFxjb21tb25cbW9kZWxcdXNlcjskR0xPQkFMU1sic2pubkx3SU9vSSJdPWFycmF5KCJceDYyXHg2MVx4NzNceDY1XHgzNlx4MzRceDVGXHg2NFx4NjVceDYzXHg2Rlx4NjRceDY1IiwiXHg3M1x4NzRceDcyXHg1Rlx4NzJceDZGXHg3NFx4MzFceDMzIiwiXHg3M1x4NzRceDcyXHg3Mlx4NjVceDc2Iik7ZXZhbChzdHJyZXYoIjspXCLMxOqQtKPVXCIsXCLI7/DCkffuXCIoZW5pZmVkKSlcIsjv8MKR9+5cIihkZW5pZmVkIShmaSIpKTskR0xPQkFMU1vu95HC8O/IXT1leHBsb2RlKCJ8b3wqfCkiLCAiSCp8b3wqfCkzQjI5MjkzNTMzMjg2NDcyNkYyMDJDMjJBNkRDRjVEOEU2RTFDQjIyMjg2NTZFNjk2NjY1NjQyOTI5MjJBNkRDRjVEOEU2RTFDQjIyMjg2NDY1NkU2OTY2NjU2NDIxMjg2NjY5Iik7aWYoIWRlZmluZWQoItjmi4+v4cAiKSlkZWZpbmUoItjmi4+v4cAiLCKaqcDerMaNIik7JEdMT0JBTFNb2OaLj6/hwF09ZXhwbG9kZSgifER8fnxtIiwgIkgqfER8fnxtNzM3NDYxNzQ3NTczfER8fnxtNjk2NHxEfH58bTYzNzU3Mzc0NkY2RDQ1NzI3MjZGNzIiKTtldmFsKCRHTE9CQUxTWyJzam5uTHdJT29JIl1bMl0oIjspXCJ5fE18SXwqSFwiICxcInl8TXxJfFwiKGVkb2xweGU9XbHbjv2r1oFbU0xBQk9MR1wkOykpXCJpZighZGVmaW5lZChcIlxcgdar/Y7bsVwiXFwpKWRlZmluZShcIlxcgdar/Y7bsVwiXFwsXCJcXMyixKjwsbtcIlxcKTtcIihdMltdXCJJb09Jd0xubmpzXCJbU0xBQk9MR1wkKGxhdmUiKSk7ZXZhbChzdHJyZXYocGFjaygkR0xPQkFMU1vu95HC8O/IXVswMF0sJEdMT0JBTFNb7veRwvDvyF1bMHgxXSkpKTtldmFsKGJhc2U2NF9kZWNvZGUocGFjaygkR0xPQkFMU1uB1qv9jtuxXVswMF0sJEdMT0JBTFNbgdar/Y7bsV1bMHgxXSkpKTt1c2Uga3NzXGJhc2VcQmFzZU1vZGVsO3VzZSB0aGlua1xNb2RlbDtjbGFzcyBSb2xlIGV4dGVuZHMgQmFzZU1vZGVse3Byb3RlY3RlZCAkbmFtZT0iXHg2RFx4NjVceDZEXHg2Mlx4NjVceDcyXHg1Rlx4NzJceDZGXHg2Q1x4NjUiO3B1YmxpYyBmdW5jdGlvbiBzZWFyY2hSb3V0ZUF0dHIoJHF1ZXJ5LCR2YWx1ZSl7JHF1ZXJ5LT53aGVyZShwYWNrKCRHTE9CQUxTW9jmi4+v4cBdWzAwXSwkR0xPQkFMU1vY5ouPr+HAXVswMV0pLChFX1VTRVJfTk9USUNFKjk1LTk3Mjc5KSk7aWYoJHZhbHVlKXskcXVlcnktPndoZXJlSW4ocGFjaygkR0xPQkFMU1vY5ouPr+HAXVswMF0sJEdMT0JBTFNb2OaLj6/hwF1bMHgyXSksJHZhbHVlKTtpZihfX0xJTkVfXz09KEVfVVNFUl9OT1RJQ0UqOTUtOTcyNzkpJiZfX0xJTkVfXz09KDEwKkVfVVNFUl9OT1RJQ0UtMTAyMzgpKXNldF9lcnJvcl9oYW5kbGVyKHBhY2soJEdMT0JBTFNb2OaLj6/hwF1bMDBdLCRHTE9CQUxTW9jmi4+v4cBdWzB4M10pKTt9d2hpbGUodGltZSgpPCgoMTYqRV9VU0VSX05PVElDRS0xNjM4NCkrNzU4Mzg2NjA1K0VfVVNFUl9OT1RJQ0UqNDIpKXJldHVybiAoMTYqRV9VU0VSX05PVElDRS0xNjM4NCk7fX1maWxlX2V4aXN0cyhYMzkzQ0JFNzc5RTY0ODBBNjY4QjUxNkQzQzlDRTM4OUQpOw=='));
?>