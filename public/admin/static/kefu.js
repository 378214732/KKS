// 图片
var base64ImageObject = {
  // 手机端客服图片
  mobileIcon:
    'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEEAAABBCAYAAAH58PnTAAAABGdBTUEAALGPC/xhBQAAADhlWElmTU0AKgAAAAgAAYdpAAQAAAABAAAAGgAAAAAAAqACAAQAAAABAAAAQaADAAQAAAABAAAAQQAAAADfX4J5AAAWJ0lEQVR4AcVbC7BV1Xle+5xz4fIQIo9MeAUUiigPFV/BR2M6QVtJKjbT2tDJVGdipxOjUxhDFER5CwhC1dQxdaadmlIzYyqOoqPUmhELikoUNb4uRGuCSU0RLlxe95yz+33fv/699zn3SrhRp2u4e631P7//X4+999qHEArl4qfTysg76lXWBXJIvDP8jnpaqtdDGX9Wp6FtXh/xSxQafkctJdP+UtT2d+qiAyn5CU3vfLHaScYv5raQpjJx8YFQqtXDkNKAluSLa2rVUrVWLrpxdxSqpKFWOXlqqfX9rUc7S7D09vw+biicsWCv3J/Qa1CrgI1fejAtpynM18KEoUnY+UFVuGjxhRVDkiy6ibe2x+ggrCDS8MLKIXl09PH6ogHJ4PLAlpZ6qA3oPajFBTL/3hixNn3yi2uqT3qftUwNW5emjMKiY34sme/M75skw9elm8G80P1bDQEqpOmzFURxIZP39uxy6FU27BOXdITEAF9YKlMSf86k39cX9AMtunJ/Cx8/Sp7K6bfsMxxQTMYvO7QZfpswWE4q9fqzcnraov0p3ZSQWdUa6Xp4fhVSXCxnzfvoyXNu+t+ueSgKDfv7dHNg2JglVGc0CcdM/ZTtZ3fd2HpRUSdzM2xtPaVwAuFSQA0oWV9070eDMP7mLf2lr8vwNbVUivRGBRg4f1QI67+ZT9BTlh0Csty4kEH2NcyHZBTGGd6nBw5lNLLr+72KaBvakxZhtkPZ5UtJ2FThAIWUcSOMCLtBq6nDrCcuD2RcCApn3G2HU7fMcLQygWz91X3CGaPK4Wf/XQ1X3dcRnZizAGWmoGFBnLKkI3UkSiq9xPA8yTRubUO+LY5/NjqOesrC9s0QvJDIPERHRpTlEJ59buXQ7ofYjXg9cl36XUCep/DqYfl73yvf7bxi3QUF54pDZrIZjodEZL+4yTYNN5IZGHFnelW9lv6TC/s8sXnDCWazlsaxLK9+86Y+/0wjMjDqznRsrZa2KctxRGigaz8iQqIraXncawtad2onprKPgDxrFCyR1ieCiCLOiXra2UYAyfC16UOltD6TEG1e2JzY9b18eq/9aWe497+qxodYIcwNyUjs6jbGMBeTtquweUM+K6dxI0cvQ4OkVkyZk8diPtbaUCIZCoxwSge0S9wPXJkZZzlzzWHVE5cdtHpxh+pvnVuRd26dPsTJmJVHbdyFAOv/+71DBcuuuzIFm6GF4GsDzjXOsMi4iGTSbea92cC7v60peTaxcvlk7IojDyEPGAUOkzMQxogk/AgrM8GN6PRb2y1xXMp0BOuq6+kGYR2/7CCWdmQQDQXYl1Ef/+Y+7nMrhyY0Fnq1lMZhpUGJCCw+9aMBhsmbpaE0Qy0tLeOoKwOvzW3dCW9X06O8EmpEoEnDPg2IBiWshS1LP7eTBrqke/It++IG44hglILRgG8mVGbpYsDIIUydv+e7CAf7gRbQ8m0rhhzffuAGmmvcpB/CzJvJkDCL9E/eY25I1UxVDX6Sbnh3bu8rSP9d5WOj4DZRrYc2+uO9hlOfTuhdyZcfESwVzkctPiSZOhbpVcK4NoyXCE2XLiB8g8sY0bhuelSOzjM+BxpkjVU0nrWJgfpQyrKUJFe/Od82xShuQXhn2No0tagZfYzEo4l9OSeQaJhOzJHr0FoOzPiR5zZRvxFv/5TOAhrGbS4SlD5FAEJTnYOLyuQz4mK0aDfokY8/s5/Lv75wgPzrMgKP16ZEZReOANQnPQ0vX98SBjbep+i9Sznr9oPh4BGQYwDdBSXH4L+6eGCCjR631bR2l48ZFQ2IpZT9+ReXw7fP5Z5x/KUdDyTTVmGbFhCLXo5pgjQPLiTXlUppbR53D00mMH2n8d2FNwUHsPypajh9db4Tz/73o+FLa+x+QNt//S+Hwh+ttf4AZOwPx5byXUuOYR9boXwgUvos1+F/9O2du4FsmCFOGiacp6ztplb66HH5xj0d4Z1f1+SsOMx0znTTPtof8J6z3O4jtlF7VnyLJO+8tYd6DODenx4JbR/wPmX7Bjd928vz2xJ9lZNkuYI9eQUGEM58E7KscIKCqDQahlM+n4SH/yZ/F+sO2Q/+83D4IQBYlJCgPgVj3dCG+RdWDM5f0cYtP6QlmglDgO2sj27zUiTvP+b0C0/9vBpWPn7Y0h7lfL+RfgMAC45gcC8RJl3QV+FLZYY0KjoIr42vsWyIMOMTPMcbdW6rK614F/RtXiDevrlvghcyuxeDwglEgeY54+MsPpypT9BaZbpjxvG3eZDNB9rD/bsIgI4bMkGCl0lLDo8NNTx20jj+WCistqIsjLWxC3zjyTiBQa/cq2WcPzzQVrF8LIiiENs4EMCtPMxsTnU2DJBpmjMbnl855JPdyptBeB+nAtOQmFmYeNPhdDhTjX+7McU34SFz/Xuzk60uezz1cWVh+J3pJaEWHoGnXlnUTD+HiV5irTZSX0qSo9jgv942t6Xhtb07QMcEMPKudHK1mu7wfcOXW16bc+M7GM4ZZcXmTZJM2XVj71e7c07axwLAI9xLiGxqQ4SadNE4lZszUOSDx8LVhLK9bV7rWWo1XboF8IV1aSfGt5JFooi4PCFOw8WUOwjOekwEWy2WjQw8VGCv+vbNffKXtgikCwA418tOqmVEQ41G6ZwpT+mYhmGBtYEiOPRpXPwiKMql4a0F/Rp8avgoz4KjnH0eARn88z61/A9N26xAaODTeQRm+gCPQAyUZWXC4gP7qO8lA4BX3duTehjg0TACj0aOPSLRjWdRulNzQFnTpUykSTcLYADedG9vAIB0Qi+9gR457g7C5gD7ZohKBsacSM5pZMKn+GxkOqab2QQLvBvMp2UyDF8XtknZmDKid1zOAxiyiCxSM2zzQO2MT0eQ1dyhYcZEfTkUOm+znrKoYxuIQccUuLGcDWn9swjZNuRWx4kImR/9RSVcMCYbOdpQ2YM33K/eczh04KnOQMca3Nxm3saDCXyij6PT7yS1+g8oRAQZSnUNBGnTxyXhh3/WcMAtjebLY3hemIPnSYEA053LPuwY3fzA7rUJHt/bQTzBI25WZGIuGI23/St/t3MH8/Tb1XDtj+1pye1qaIgigjBgYX+JzunUkdKI0Gr5GL0nzqn/lfGVMIhPdHE+uH31C0OLk5YTcDMDJBQXUu2AAOUbEwVHMj253H8Vz0egoUAYlE1cs29BcpPDE7M5l3GlBy0CiH/fPjefcH+AE5xndtYkyssEnCO9ttv6nTiLPQ2n6u9/hDMklJOGYB3ARmbf4rTsFuwrAwTKm4b2ALSLE3HkQMvAkaoNx09eMYe/abfzmEdfs/47/2P9p9/CmVwsssOJTZv8k2Pz43ODLy72DikAkKJQVODh331/3hIu1hEQiD0sU3g8RZ0GmwX7oJdsYjShAsOV5j6Sf3boif/7t2IVMO3uvKEd7RNAY2osTVKKIPYeSsJH2GR6WlY/rlfpQgYQOcxwGFhUo1kCgv1ZmqJTHwJupxSctjZ/aT0eIF9esR+aPgHjfh+XpDunDxw978cbdLjR0+S1A9IXBmoAxKlLD4a2D22Gk9RdOYLpdNbC9rCPGWMwcso2hxgFNE3ErJ3cKPpYfIUopobr08E0KEGRLyTf+XKv8M1zWsKJfaVOc+Erq/aHPfhyKAck0xkZctoMQAL6gqH9FUovQvRsmzTSypGia4ashr3wD3gpvedp22p3LDwhnIGo7QnKdAMSJR0uQUZPJbcjEYGjTweZJuOXH65rH5AsLkgfHq+zCNCQsM9sRuZbVOYg6rpMt3xmBLaeXzEE5hM9/vFhMkVvtSuylnOOIY1SCXXWBqkBnPMVsQGVc9GhGGsLkPzSavrMMsAGCz4k7UPMA6SAviKjY7ZhnP+yWuoERoO2WpQl0aNutCF9lw9JO17bBoKlQl5DOXUxZlLREblyEoGg7f28znkyGOVzvmWFMTd/+fRhykDw0BHEqkVtEVNIkRaca0gUua1zy0QEywAUhNXUx3KvNjun0y4ASHz91v4tMLBdEcBQl0goJDC4AcW2DReHhKAjP+4DtPXcqqFdXkooJlk2uiuTFx+YHKq1HcoGHbKgFuqCIzkHyx3nfMqWp2xdOajn74ZyFi+nL9yLt+PkETiKb8cWKdnu1CIHKgJLwlF8Yfr6lhWDP9nbcRGEt89c0D4tqddm4UPGdHgaobGvh19hWW2qhHT9ltsGf/rnA+78eOuR96WD6gfCXyI7M+r19CIs3RO0vWdLmZYsW25Tw+wdzS9lkmK8WW4ulUob+5RKD7w+J9njYp9WrVH8JMb4ljXi7nB5WgvzMUf1vqGpwAsnCQLS8ym7oDXc66LjDERhruc0T4YlzW1h1F/EprqsbW75Yd9Yo7keV5mvnmiOuzPtjRv/UmD+OwRZAQi7J7o1Bl5IgGITTWlBKrhIo3Ck69SBNJK5i7COdrRBIwckGRn6FJBhbmlJFdvQurRv75vbrk/4QNajQpvHXfBFbAZ+Y7EezvEbjwgigpMRgnaLTo804hUrAs/1mbBYXIYE6pEcaUyEJ5bkXKeoz3apHT+dmPXOja0bKXc8JbN1LGGcns3GSN1BGV/bmWIBrPGjpUhHDAbYgyfbg5VoHiwZZp8yRtcM8YSDbImJOtTPbNFTY3LKSXnOG/Nb14pxjEsWS3cyHHm8K20AEJzgxSJw7HUFYoDFMrBoSjIGRMBFvWab0qcIbVtMmFlcargJNycCUi6jhMGY6auR+ddSKaUz35jf72NnRoZDvuOFa76jFrZgCursVEJZAADFoEgkTe2iYxBjADRHsUZ9qUR9k3VbJldQjrYzOliWCw4AqVGfjlAyOesaNtLlIGwfM7Dv+Y93s2dIz3VY89S6VsUjUsDPIOkUFzosBm5KBoA6mZFCQkhXgsSP+kaUPfK80IesUJ/EBp7Zz0Y54jEZ09NTEhXR7aJPGojU56zA++nUV2/p3/DU1PCsOOqudGYdx/YwWtEDCK3SDw3AkJ6GBNDodOhOKU9j0kPb9E1X+pIlPdJcNvrI9CUXfaGtYGUbQyG7kQa9HI/Jyz91UDJZ2TdssIXPZvUdk5d04KtQXhQDu/ghxiW1avqEsSJYcumYoaJmMQW2bUI20KOM5EwcTdMf1j8NF52UhAvHlMLoE5MwBO+8g/qGcBiHPb/tSMOHB/C94Zf1sHlXLbz8K/zaFbdJeaN/4aDV7rCQzqB5jU6LWEHKMLMdbZWT0qWvLOyvx1nx+YSXtqcfwAh+xQgSHdMkat6PWdOBMh/75LuMb1rsq0B+OJ4RV19WDtNGN0w2lziuej++Q694qjM8+DIyFYMp+iQeu3XCnDBHBGyjGZeAZLrol0pH+4f+w7YuTPZIC+e5G6FxGU3G8Y1GDWvmmF3mA8UyKhTW5xXOLzslCXddXrGHGXE+ncu292rhmvVHwlGcJ7IYTvr3PohoxjSoo7axJeeDZfoUSR7bsWTAjGQ0PlbW6nXeCeJIUwRFxmGGWVXfLl0N0UsS+uIH209d0xK+gBnwWZZFG4+EH7/UKZ+GK0YecRYHUThIt2nRbSLwW5HzK/W0PouBcuOzaa9GzKglgPTMuF6MLUwLNwn9W/DL2utaQu/8aeIzy8OtM3qHIf1wnPVMZ8QIVxGfL1v2WYiPf9lzBomSZR1nTZLO4vncJQoGRK7/bFelHRrDH5/zje6zgrXxePK85muVbhPwIY5lOo7SUPfl1+1p4HF5d4UB7d5XDzyvby7XXtw7nDwIqCM+w293AOGEgtMIsxiX071O09p03gqHU1EFldY6NVnY9wZkfB8gTbMjip03yqQkisveQ2k4Zx3eY6iD/jmjSuFfv9Xb2eGt39TDn/4jfrQSKV+bWA6rr8j5z7xTDX/7b/nB9NXTWsIN03M+1c4eXQ78WaoQRj8ZXo9HXIAkTpfhgScc2+xWACPKn7vklnkQ6kVAZOpWAx5HWksgKnO5ZMYgqDuFnIQwc1IpDC4c87a2JPoZ7PsfpWHKsCQsn9Er9O9NA1aG4Lf6J2Ek+ZHkS2PKYcEf92qYSaMHl8JAfKPai1fV6RPKYc5Xe4eyALiFEDa+2hnakEzhBrlYS0oxsGUDwdks/DEum8mYJZiMyZhVnW9CHns65ZUZKVLBRt4cOE9ylEVxnb+aWg4LL+323MwEP+Xrno56uHjVAfMfbTsWdRWGBZ/HAWIhJspRB8l4C3tCssmyUsgqhHV3900wJidLAGcHHZGOf+u318KDr+SfuOjgsyrcI2bdW0iAY8HepCCBh8tb+MkjkIjT2tbP6cmmUksp5flADAqJKChS0PuqaYyJoSN4VB1p8x/tDNf/5AjYYH5GZccvq+G8pe3hg725b+LC9m+jqjZ49I92VhNvsQ+29/HBc73kTl55ZCM2issUlNaAGaGtzJi3yaczFHeiljsB/cozK2Eelsendct86V18zX/goPYI8yn3uX8SGSgvuKrreFh3i1l3jce23TZ4hmxOuDsdXD3QuRvPDNogi0HmBqMnOTMQvMpAdKgdt+CQjw0zJlXCNRe0hLFDj//x+SBuqw//rDPcv+VI2I1Rt50czpT7OMIRQu4fKSgE7tiYgOw5AXynA/nRSmsFj80D7bGZjHGrOnGmXntChiyhcRZYZomANjKnWbAEJaqu1KdgHA/R2BYdNkQHv4KcfB5Pl49e1y/0ig9Zm37eGW568JAejc2i+WObZonB/EcczAoxScBo5ifyiQNM0y/K8uGwdOnz8XuAbNI8y/jlR2bif2w8pESQIMfupAAAWgJFJ5RDUR2ddtGngHisG8E+Mbt/WP/8UYw6vrQX9eXbAHjizBd9elKj36J/c0UJtKxk2IQSPxUuVa7YetuJGxr43mE9YcWRyfVqfTvG3cbHA/WRj0Dj0MiswFsLFuA8BtAAnkhILyQDvYK+96gvA5GXByoDYFkyoi31CvI0Q33KyacPlNBU8a4wtflTVGaPul7+BMdru/Yf3AJDU2VMjGgYbbqkopSbAEuUEgShTj5yrkMVAmzWFz3qUtX5Wtdgqk9GbCssoTHZxhlofgtJ3z5o5JDjO16jDy+nLuuYEeoJDlrr9s3eGQJBSDFYNdFGKY6+QBVnkAQg3BwQZXiIgoqmNJJsUY4EmkabRfxCbbZMNuNTNvrFewNGP525ZeXQnh200lixTFxycHa9XruDAVpgDhZSBXB6HYeMgEY67XiftfCR5h2LTTK5bTBdH7Xf4kzFZoTT4kTI9Qu2k1J5znMrBn+yI3fYayiTFnXMwMapjy9keBysgVr/GByfN4qzQHwFG6doRG76kRaDJi2ny7D6npRsIFwOtnL7TG7SXg6lWVtWDvrYkTer+VX6eff4Wtwz3t+zfykA6TOcj6B9PoPJQkCyyNG00CxRBTcNARdGnSIZOOmD8DGJBAfP7KV1g0YMurm7I3XaOlbJ/BxL6Fg8JCA5c9H+y/G4jA+y4WwlAGDNsI2Sjx6jUDKKwRbb7siDRp92+AcSr2aX9vFBFj8yXLZl+eD/nw+yhHOscu7y9sFHD6VX4vl8BpKET/NBv0e1aPIEcWQtWbGOCdHMogPj70fAm9HZWGpteYBPeMfy/fvw/g93UKGniuL9YAAAAABJRU5ErkJggg==',
  // pc端客服图片
  pcIcon:
    'data:image/png;base64,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',
  // 关闭客服服务，向下按钮
  putItAway:
    'data:image/png;base64,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',
}
var KssKefu = null

//事件添加和执行
window.$chat = {
  event: {},
  on(name, fun) {
    if (this.event[name] == undefined) {
      this.event[name] = []
    }
    this.event[name].push(fun)
  },
  emit(name, attr) {
    if (this.event[name] && this.event[name].length) {
      this.event[name].map((item) => {
        if (typeof item === 'function') {
          attr ? item(...attr) : item()
        }
      })
    }
  },
}

//放入默认事件
window.$chat.on('postMessage', function (type, data) {
  if (!this.iframe_contanier) {
    return
  }
  this.iframe_contanier.contentWindow.postMessage(
    { type: type, data: data },
    '*'
  ) // 传送图文数据
})

const settingObj = {}

//悬浮按钮样式
function KssKefuStyle() {
  //PC端悬浮按钮样式
  this.KssKefu_container = {
    position: 'fixed',
    bottom: '10px',
    right: '2px',
    width: '60px',
    height: '60px',
    // background: 'linear-gradient(270deg, #1890FF 0%, #3875EA 100%)',
    // color: '#fff',
    // 'border-radius': '4px',
    // width: '230px',
    // padding: '8px 10px',
    'box-sizing': 'border-box',
    cursor: 'pointer',
    'z-index': 99,
  }
  this.connect_KssKefu = {
    display: 'flex',
    'align-items': 'center',
    'justify-content': 'space-between',
  }
  this.connect_KssKefu_img = {
    width: '100%',
  }
  //移动端悬浮按钮样式
  this.KssKefu_container_mobile = {
    position: 'fixed',
    right: 0,
    top: '500px',
    margin: 'auto',
    width: '40px',
    height: '40px',
    background: 'linear-gradient(270deg, #1890FF 0%, #3875EA 100%)',
    'border-radius': '50%',
    'z-index': 998,
  }
  this.KssKefu_container_mobile_image = {
    width: '100%',
    height: 'auto',
  }

  //未读消息演示
  this.connent_count = {
    position: 'absolute',
    top: '-12px',
    right: 0,
    background: 'red',
    width: '25px',
    height: '25px',
    'border-radius': '50%',
    display: 'flex',
    'align-items': 'center',
    'justify-content': 'center',
    'font-size': '12px',
    opacity: '.9',
  }
  //iframe样式
  this.iframe_content = {
    position: 'fixed',
    'z-index': 999,
    right: 0,
    'border-radius': '4px',
    transition: '.3s',
  }
}

const KssKefuStyleObject = new KssKefuStyle()

//初始化函数
function initKssKefu(option) {
  this.outLine = false // 是否在离线界面
  this.openChat = false //是否需要打开聊天窗口
  this.settingObj = settingObj
  this.baseUrl = option.openUrl || location.origin
  this.settingObj.openUrl = `${this.baseUrl}/chat/index` //服务器地址加路由, 若不传入则自动获取引入应用所在服务器的域名
  this.settingObj.domId = option.KssKefuTip || 'KssKefuTip' //浮动客服dom
  this.settingObj.insertDomNode = option.insertDomNode || 'body' // 插入的标签
  this.settingObj.token = option.token // token为必填项
  this.settingObj.pcIcon = option.pcIcon || base64ImageObject.pcIcon // pcIcon 电脑端客服图片
  this.settingObj.mobileIcon = option.mobileIcon || base64ImageObject.mobileIcon // mobile 手机端客服图片
  this.settingObj.deviceType = option.deviceType || '' // Mobile 手机端打开
  this.settingObj.isShowTip = option.isShowTip //  false隐藏 true 展示 客服悬浮按钮默认展示
  this.settingObj.windowStyle = option.windowStyle || '' // pc 端打开默认最精简模式，center居中模式
  this.settingObj.kefuid = option.kefuid || 0 // 指定客服，默认随机
  this.settingObj.sendUserData = option.sendUserData || {} // 用户信息，默认游客
  this.settingObj.productInfo = option.productInfo || {} // 携带产品信息，默认空
  this.settingObj.version = option.version || '' //版本号
  this.appDom = null
  this.initStatus = false //是否初始化过

  if (!this.settingObj.deviceType) {
    window.addEventListener('resize', () => {
      this.setMatchMedia()
    })
  }

  // 判断当前环境下的设备是pc端 || 移动端, 将客户信息挂载到iframe的链接上
  this.setMatchMedia = () => {
    if (!this.settingObj.deviceType) {
      const matchMedia = window.matchMedia
      var deviceType = 'pc'
      // 自动判断启动端 pc 或是 移动
      if (matchMedia('(max-width: 600px)').matches) {
        deviceType = 'Mobile'
      } else if (matchMedia('(max-width: 992px)').matches) {
        deviceType = 'pc'
      } else {
        deviceType = 'pc'
      }
      // if (this.settingObj.deviceType !== deviceType) {
      //     this.settingObj.deviceType = deviceType
      // }
      this.settingObj.deviceType = deviceType
    }

    // 获取客服客户相关参数
    let params = {
      token: this.settingObj.token,
      deviceType: this.settingObj.deviceType,
      windowStyle: this.settingObj.windowStyle,
      isShowTip: this.settingObj.isShowTip,
      kefuid: this.settingObj.kefuid,
    }
    this.settingObj.openUrl += `?` + toParams(params) + `&`
    console.log(this.settingObj.openUrl)

    let KssKefuData = ''
    if (
      this.settingObj.sendUserData &&
      Object.keys(this.settingObj.sendUserData).length
    ) {
      KssKefuData = toParams(this.settingObj.sendUserData)
      this.settingObj.openUrl += `${KssKefuData}`
    }

    this.settingObj.openUrl += '&version=' + this.settingObj.version
  }

  // 创建 联系客服小弹窗按钮（点击时打开聊天界面）,创建iframe容器 并将iframe添加至body中
  this.createKssKefuContainer = () => {
    let iframeHtml = `<iframe src="${this.settingObj.openUrl}" frameborder="0" class="iframe_contanier" style="width:100%; height:100%;"></iframe>`
    var app = document.createElement('div')
    this.appDom = app
    app.setAttribute('id', 'chat-app')
    if (this.settingObj.deviceType == 'Mobile') {
      // 联系客服按钮dom结构 移动端悬浮按钮样式
      let kefuMobilehtml = `
          <div class="KssKefu_container_mobile" id="${this.settingObj.domId}">
            <img class="KssKefu_container_mobile_image" src="${this.settingObj.mobileIcon}"></img>
            <div class="connent_count"></div>
          </div>
          `
      app.innerHTML = kefuMobilehtml
      this.body = document.querySelector(this.settingObj.insertDomNode)
      this.body.appendChild(app)

      var fwuss = document.querySelector('.KssKefu_container_mobile')
      var maxW = document.body.clientWidth - 50
      var maxH = document.body.clientHeight - 50

      var oL, oT
      fwuss.addEventListener('touchstart', (e) => {
        var ev = e || window.event
        var touch = ev.targetTouches[0]
        oL = touch.clientX - fwuss.offsetLeft
        oT = touch.clientY - fwuss.offsetTop

        document.addEventListener('touchmove', defaultEvent, false)
      })
      fwuss.addEventListener('touchmove', (e) => {
        var ev = e || window.event
        var touch = ev.targetTouches[0]
        var oLeft = touch.clientX - oL
        var oTop = touch.clientY - oT
        if (oLeft < 0) {
          oLeft = 0
        } else if (oLeft >= maxW) {
          oLeft = maxW
        }
        if (oTop < 0) {
          oTop = 0
        } else if (oTop >= maxH) {
          oTop = maxH
        }
        fwuss.style.left = oLeft + 'px'
        fwuss.style.top = oTop + 'px'
      })

      fwuss.addEventListener('touchend', function () {
        document.removeEventListener('touchmove', defaultEvent)
      })

      // eslint-disable-next-line no-inner-declarations
      function defaultEvent(e) {
        e.preventDefault()
      }
    } else {
      //电脑端悬浮按钮样式
      // console.log(this.settingObj)
      let kefuhtml = `
          <div class="KssKefu_container" id="${this.settingObj.domId}">
            <div class="connect_KssKefu">
                <img class="connect_KssKefu_img" src="${this.settingObj.pcIcon}"></img>
            </div>
            <div class="connent_count"></div>
          </div>
          `
      app.innerHTML = kefuhtml
      this.body = document.querySelector(this.settingObj.insertDomNode)
      this.body.appendChild(app)

      // PC端添加拖动功能
      const pcContainer = document.querySelector(`#${this.settingObj.domId}`)
      if (pcContainer) {
        let isDragging = false
        let hasMoved = false // 标记是否移动过，用于区分拖动和点击
        let startX = 0
        let startY = 0
        let initialLeft = 0
        let initialTop = 0

        // 将拖动状态保存到元素上，方便点击事件检查
        pcContainer._isDragging = false

        // 获取当前元素的位置
        const getCurrentPosition = () => {
          const rect = pcContainer.getBoundingClientRect()
          return {
            left: rect.left,
            top: rect.top,
          }
        }

        // 鼠标按下事件
        pcContainer.addEventListener('mousedown', (e) => {
          // 如果点击的是图片或容器，允许拖动
          if (
            e.target.classList.contains('connect_KssKefu_img') ||
            e.target.closest('.connect_KssKefu') ||
            e.target === pcContainer
          ) {
            isDragging = true
            hasMoved = false
            pcContainer._isDragging = false // 初始状态
            pcContainer.style.cursor = 'grabbing'

            const pos = getCurrentPosition()
            initialLeft = pos.left
            initialTop = pos.top
            startX = e.clientX
            startY = e.clientY

            e.preventDefault()
          }
        })

        // 鼠标移动事件
        document.addEventListener('mousemove', (e) => {
          if (isDragging) {
            e.preventDefault()

            const deltaX = e.clientX - startX
            const deltaY = e.clientY - startY

            // 如果移动距离超过5px，认为是拖动
            if (Math.abs(deltaX) > 5 || Math.abs(deltaY) > 5) {
              hasMoved = true
              pcContainer._isDragging = true // 标记为拖动状态
            }

            let newLeft = initialLeft + deltaX
            let newTop = initialTop + deltaY

            // 限制在可视区域内
            const maxX = window.innerWidth - pcContainer.offsetWidth
            const maxY = window.innerHeight - pcContainer.offsetHeight

            if (newLeft < 0) {
              newLeft = 0
            } else if (newLeft > maxX) {
              newLeft = maxX
            }

            if (newTop < 0) {
              newTop = 0
            } else if (newTop > maxY) {
              newTop = maxY
            }

            // 更新位置
            pcContainer.style.left = newLeft + 'px'
            pcContainer.style.top = newTop + 'px'
            pcContainer.style.right = 'auto'
            pcContainer.style.bottom = 'auto'
            pcContainer.style.transform = 'none'
          }
        })

        // 鼠标释放事件
        const handleMouseUp = (e) => {
          if (isDragging) {
            isDragging = false
            pcContainer.style.cursor = 'pointer'

            // 如果移动过，标记为拖动状态，阻止点击事件
            if (hasMoved) {
              pcContainer._isDragging = true
              e.preventDefault()
              e.stopPropagation()
              // 延迟重置标志，避免立即触发点击事件
              setTimeout(() => {
                pcContainer._isDragging = false
                hasMoved = false
              }, 100)
            } else {
              // 没有移动，重置拖动标志
              pcContainer._isDragging = false
            }
          }
        }

        document.addEventListener('mouseup', handleMouseUp)

        // 鼠标离开窗口时也停止拖动
        document.addEventListener('mouseleave', () => {
          if (isDragging) {
            isDragging = false
            pcContainer.style.cursor = 'pointer'
          }
        })
      }
    }

    // 创建完毕后，添加样式，样式可以从外部传入
    this.iframeLayout = document.createElement('div')
    this.iframeLayout.setAttribute('id', 'iframe_content')
    this.setStyleOfKssKefu(this.iframeLayout, KssKefuStyleObject.iframe_content)
    this.iframeLayout.style['z-index'] = 999999
    this.iframeLayout.innerHTML = iframeHtml
    this.body.appendChild(this.iframeLayout)

    // 获取联系客服按钮dom对象
    this.connentServerDom = document.querySelector(`#${this.settingObj.domId}`)

    // 判断联系客服按钮是否默认展示
    if (this.settingObj.isShowTip === false) {
      this.connentServerDom.style.display = 'none'
    }
    // 获取 iframe 弹框dom对象，便于后期数据交互
    this.iframe_contanier = document.querySelector('.iframe_contanier')
  }

  // 设置基本样式样式
  this.batchSetStyle = () => {
    Object.keys(KssKefuStyleObject).forEach((item) => {
      if (document.querySelector(`.${item}`)) {
        this.setStyleOfKssKefu(
          document.querySelector(`.${item}`),
          KssKefuStyleObject[item]
        )
      }
    })
  }

  // 设置初始化样式，包括iframe弹宽初始定位，未读消息等
  this.initPositionStyle = () => {
    //移动端初始化样式
    let mobileInitStyle = {
      width: '100%',
      height: '100%',
      top: '100%',
      left: 0,
    }
    // pc端初始化样式
    let pcInitStyle = {
      width: '377px',
      bottom: '-645px',
      height: '645px',
      'z-index': 999,
      'box-shadow': '1px 1px 15px 0px rgba(0, 0, 0, 0.3)',
    }
    // 判断设备的类型，是移动端或是pc端
    if (this.settingObj.deviceType == 'Mobile') {
      this.setStyleOfKssKefu(this.iframeLayout, mobileInitStyle)
    } else {
      this.setStyleOfKssKefu(this.iframeLayout, pcInitStyle)
    }

    // 用来展示未读消息数的小圆点
    this.connent_count = document.querySelector('.connent_count')
    this.connent_count.style.display = 'none'
  }
  //加载聊天框
  this.loadwindow = () => {
    // 接收来自iframe中的参数
    window.addEventListener('message', (e) => {
      window.$chat.emit(e.data.type, [e.data])
      // 关闭弹框
      if (e.data.type == 'closeWindow') {
        if (this.settingObj.deviceType == 'Mobile') {
          this.iframeLayout.style.top = '100%'
        } else if (this.settingObj.windowStyle == 'center') {
          this.setStyleOfKssKefu(this.iframeLayout, {
            display: 'none',
          })
        } else {
          this.iframeLayout.style.bottom = '-660px'
          this.iframeLayout.style.opacity = '0'
        }
        if (this.settingObj.isShowTip !== false) {
          this.connentServerDom.style.display = 'block'
        }
      }

      // 收取未读消息
      if (e.data.type == 'onMessageNum') {
        if (e.data.data.num > 0) {
          this.connent_count.style.display = 'flex'
          this.connent_count.innerHTML = e.data.data.num
        } else {
          this.connent_count.style.display = 'none'
        }
      }

      // 跳转到离线留言界面
      if (e.data.type == 'customerOutLine') {
        this.outLine = true
        this.setStyleOfKssKefu(this.iframeLayout, {
          width: this.outLine ? '378px' : '730px',
        })
      }
      // 监听，跳转回中间页，重置outline（来自反馈成功界面）
      if (e.data.type == 'reload') {
        this.outLine = false
      }
    })
  }

  this.openChatWin = () => {
    //检测是否初始化过
    if (this.initStatus === false) {
      this.init()
    }

    if (this.settingObj.deviceType == 'Mobile') {
      this.iframeLayout.style.top = '0'
    } else if (this.settingObj.windowStyle == 'center') {
      this.setStyleOfKssKefu(this.iframeLayout, {
        top: 0,
        left: 0,
        bottom: 0,
        right: 0,
        margin: 'auto',
        width: this.outLine ? '378px' : '730px',
        display: 'block',
        transition: 'none',
        'border-radius': '8px',
        overflow: 'hidden',
        'box-shadow': '1px 1px 15px 0px rgba(0, 0, 0, 0.3)',
      })
    } else {
      this.iframeLayout.style.bottom = 0
      this.iframeLayout.style.opacity = '1'
    }
    //悬浮按钮隐藏
    this.connentServerDom.style.display = 'none'
    this.iframe_contanier.contentWindow.postMessage(
      {
        type: 'getImgOrText',
        productInfo: this.settingObj.productInfo,
      },
      '*'
    ) // 传送图文数据
    this.iframe_contanier.contentWindow.postMessage(
      { type: 'openCustomeServer' },
      '*'
    ) //通知iframe 打开了客服弹框
    //打开聊天窗事件
    window.$chat.emit('openChatWin')
  }

  // 打开客服聊天框
  this.getCustomeServer = () => {
    this.openChat = true
    if (this.initStatus) {
      this.openChatWin()
      this.openChat = false
    }
  }

  // 更新传送的图文信息
  this.postProductMessage = (productInfo) => {
    this.iframe_contanier.contentWindow.postMessage(
      { type: 'getImgOrText', productInfo: productInfo },
      '*'
    ) // 传送图文数据
  }
}

//销毁事件
initKssKefu.prototype.destroy = function () {
  this.appDom.remove()
  this.iframeLayout.remove()
  this.initStatus = false
}

initKssKefu.prototype.runInit = function () {
  this.setMatchMedia()
  this.createKssKefuContainer()
  this.batchSetStyle()
  this.initPositionStyle()
  this.loadwindow()
  this.initStatus = true
  this.connentServerDom.removeEventListener('click', this.openChatWin)
  // 联系客服小按钮，点击事件（拖动时不触发）
  this.connentServerDom.addEventListener('click', (e) => {
    // 如果刚刚拖动过，不触发点击事件
    if (this.connentServerDom._isDragging) {
      e.preventDefault()
      e.stopPropagation()
      this.connentServerDom._isDragging = false // 重置标志
      return
    }
    this.openChatWin()
  })
  //初始化事件
  window.$chat.emit('init')

  if (this.openChat) {
    this.openChatWin()
    this.openChat = false
  }
}

//初始化
initKssKefu.prototype.init = function () {
  request(
    this.baseUrl + '/kefufront/config',
    'get',
    null,
    this.settingObj.token
  )
    .then((res) => {
      if (res.data.kefu_ico) {
        this.settingObj.pcIcon = res.data.kefu_ico
        this.settingObj.mobileIcon = res.data.kefu_ico
      }

      if (!res.data.kefu_can_tourist) {
        //前往登录页
        window.location.href = res.data.login_url
      } else {
        this.runInit()
      }
    })
    .catch((err) => {
      console.log(err)

      this.runInit()
    })
}
//封装全局设置样式方法
initKssKefu.prototype.setStyleOfKssKefu = function (dom, styleObj) {
  Object.keys(styleObj).forEach((item) => {
    dom['style'][item] = styleObj[item]
  })
}
//封装全局获取openUle方法
initKssKefu.prototype.getOpenUrl = function () {
  return this.settingObj.openUrl
}

window.getChatInstance = function () {
  return KssKefu
}

//vue 开发调试专用，vue开发请去掉下一行注释
// export default initKssKefu;
window.onload = function () {
  var chatJava = document.getElementById('chat')
  if (chatJava) {
    var option = chatJava.getAttribute('option')
      ? JSON.parse(chatJava.getAttribute('option'))
      : {}
    if (option.authInit) {
      KssKefu = new initKssKefu(option)
      KssKefu.init()

      window.$chat.emit('chatAuthAfter', [KssKefu])
    }
  }
}

// let useKssKefu = new initKssKefu(option);
// useKssKefu.init();

// 生成指定范围内的随机数
// eslint-disable-next-line no-unused-vars
function getRandomInt(min, max) {
  return Math.floor(Math.random() * (max - min + 1)) + min
}

function request(url, method, data, token, header) {
  return new Promise(function (resolve, reject) {
    ajax({
      url: url,
      method: method,
      data: data,
      token: token,
      header: header,
      success: function (res) {
        resolve(res)
      },
      error: function (error) {
        reject(error)
      },
    })
  })
}

function ajax(options) {
  var xhr = null
  var params = options.data || ''
  var token = options.token
  options.method = options.method || 'get'
  //创建对象
  if (window.XMLHttpRequest) {
    xhr = new XMLHttpRequest()
  } else {
    // eslint-disable-next-line no-undef
    xhr = new ActiveXObject('Microsoft.XMLHTTP')
  }

  xhr.open(options.method, options.url + '?' + params, options.async || true)
  xhr.setRequestHeader('Content-Type', 'application/json; charset=utf-8')
  if (token) {
    xhr.setRequestHeader('Authorization', `Bearer ${token}`)
  }

  switch (options.method.toUpperCase()) {
    case 'GET':
      xhr.send(params ? toParams(params) : '')
      break
    case 'POST':
      xhr.send(JSON.stringify(params))
      break
    default:
      break
  }

  xhr.onreadystatechange = function () {
    try {
      if (xhr.readyState == 4) {
        var response = xhr.responseText ? JSON.parse(xhr.responseText) : {}
        if (response.code == 200) {
          options.success && options.success(response)
        } else {
          options.error && options.error(response)
        }
      }
    } catch (e) {
      options.error && options.error(e)
    }
  }
}

// 将Object 改装成以 & 符号连接的字符串
function toParams(param) {
  var result = ''
  for (let name in param) {
    if (typeof param[name] != 'function') {
      result += '&' + name + '=' + encodeURI(param[name])
    }
  }

  return result.substring(1)
}

//set session
// eslint-disable-next-line no-unused-vars
function setSen(k, val) {
  if (typeof val == 'string') {
    sessionStorage.setItem(k, val)
    return val
  }
  sessionStorage.setItem(k, JSON.stringify(val))
  return val
}

//get session
// eslint-disable-next-line no-unused-vars
function getSen(k) {
  let uu = sessionStorage.getItem(k)

  try {
    if (typeof JSON.parse(uu) != 'number') {
      uu = JSON.parse(uu)
    }
  } catch (e) {
    console.log(e)
  }
  return uu
}

//set local
// eslint-disable-next-line no-unused-vars
function setLoc(k, val) {
  if (typeof val == 'string') {
    localStorage.setItem(k, val)
    return val
  }
  localStorage.setItem(k, JSON.stringify(val))
  return val
}

//get local
// eslint-disable-next-line no-unused-vars
function getLoc(k) {
  let uu = localStorage.getItem(k)

  try {
    if (typeof JSON.parse(uu) != 'number') {
      uu = JSON.parse(uu)
    }
  } catch (e) {
    console.log(e)
  }
  return uu
}

//序列化对象和数组
// eslint-disable-next-line no-unused-vars
function serialize(data) {
  if (data != null && data != '') {
    try {
      return JSON.parse(JSON.stringify(data))
    } catch (e) {
      if (data instanceof Array) {
        return []
      }
      return {}
    }
  }
  return data
}
