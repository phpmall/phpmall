function sameSign(a, b) {
    return (a ^ b) >= 0
}

function vector(a, b) {
    return {
        x: b.x - a.x,
        y: b.y - a.y
    }
}

function vectorProduct(v1, v2) {
    return v1.x * v2.y - v2.x * v1.y
}

function isPointInTrangle(p, a, b, c) {
    let pa = vector(p, a)
    let pb = vector(p, b)
    let pc = vector(p, c)

    let t1 = vectorProduct(pa, pb)
    let t2 = vectorProduct(pb, pc)
    let t3 = vectorProduct(pc, pa)

    return sameSign(t1, t2) && sameSign(t2, t3)
}

function needDelay(elem, leftCorner, currentMousePos) {
    let offset = elem.offset()

    let topLeft = {
        x: offset.left,
        y: offset.top
    }

    let bottomLeft = {
        x: offset.left,
        y: offset.top + elem.height()
    }

    return isPointInTrangle(currentMousePos, leftCorner, topLeft, bottomLeft)
}

$(function() {
    let sub = $('#category-sub')

    let activeRow
    let activeMenu

    let timer
    let mouseInSub = false

    sub.on('mouseenter', function(e) {
        mouseInSub = true
    }).on('mouseleave', function(e) {
        mouseInSub = false
    })

    let mouseTrack = []

    let mouseHandler = function(e) {
        mouseTrack.push({
            x: e.pageX,
            y: e.pageY
        })

        if(mouseTrack.length > 3) {
            mouseTrack.shift()
        }
    }

    $('#test')
        .on('mouseenter', function(e) {
            sub.removeClass('none')

            $(document).bind('mousemove', mouseHandler)
        })
        .on('mouseleave', function(e) {
            sub.addClass('none')

            if (activeRow) {
                activeRow.removeClass('active')
                activeRow = null
            }
            if (activeMenu) {
                activeMenu.addClass('none')
                activeMenu = null
            }

            $(document).unbind('mousemove', mouseHandler)
        })
        .on('mouseenter', 'li', function(e) {
            if (!activeRow) {
                activeRow = $(e.target).addClass('active')
                activeMenu = $('#'+activeRow.data('id'))
                activeMenu.removeClass('none')
                return
            }

            if(timer) {
                clearTimeout(timer)
            }

            let currentMousePos = mouseTrack[mouseTrack.length - 1]
            let leftCorner = mouseTrack[mouseTrack.length - 2]
            let delay = needDelay(sub, leftCorner, currentMousePos)

            if(delay) {
                timer = setTimeout(function() {
                    if(mouseInSub) {
                        return
                    }

                    activeRow.removeClass('active')
                    activeMenu.addClass('none')

                    activeRow = $(e.target)
                    activeRow.addClass('active')
                    activeMenu = $('#'+activeRow.data('id'))
                    activeMenu.removeClass('none')

                    timer = null
                }, 300)
            } else {
                let preActiveRow = activeRow
                let preActiveMenu = activeMenu

                activeRow = $(e.target)
                activeMenu = $('#'+activeRow.data('id'))

                preActiveRow.removeClass('active')
                preActiveMenu.addClass('none')

                activeRow.addClass('active')
                activeMenu.removeClass('none')
            }
        })
})
