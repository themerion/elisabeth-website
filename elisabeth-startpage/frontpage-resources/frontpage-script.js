let current = 800;

function drawLarge() {
    let totSize, itemRadius, imgSize;
    if(current == 800) {
        totSize = 800;
        itemRadius = 75;
        imgSize = 500;
    } else if(current == 1200) {
        totSize = 1200;
        itemRadius = 100;
        imgSize = 700;
    } else {
        throw "Expected size to be 800 or 1200";
    }

    const scaleY = 0.8;
    const large = document.querySelector("#large_content");
    const menuItems = document.querySelectorAll(".large_menu_item");

    large.style.width = totSize+"px";
    large.style.height = Math.round(totSize*scaleY)+"px";

    const startingOffset = Math.PI/12;
    const margin = 30;

    const centerX = totSize / 2;
    const centerY = (totSize*scaleY / 2);
    const increase = Math.PI*2 / menuItems.length;

    const setPolarPosition = (menuItem, radians, distance) => {
        const xpos = centerX + Math.cos(radians)*distance - itemRadius;
        const ypos = centerY + Math.sin(radians)*distance*scaleY - itemRadius;

        menuItem.style.left = xpos+"px";
        menuItem.style.top = ypos+"px";
    };

    let angle = startingOffset;
    for(const menuItem of menuItems) {
        menuItem.style.position = "absolute";
        menuItem.style.width = (itemRadius*2)+"px";
        menuItem.style.height = (itemRadius*2)+"px";

        setPolarPosition(menuItem, angle, centerX - itemRadius - margin);

        angle += increase;
    }

    const cimg = document.querySelector("#center_img");
    cimg.style.left = (centerX - imgSize/2)+"px";
    cimg.style.top = (centerY - imgSize/2)+"px";
    cimg.style.width = imgSize+"px";
    cimg.style.height = imgSize+"px";
}

window.addEventListener('resize', evt => {
    if(evalSize()) {
        drawLarge();
    }
});

function evalSize() {
    if(current != 1200 && window.innerWidth >= 2000) {
        current = 1200;
        return true;
    } else if(current != 800 && window.innerWidth < 2000) {
        current = 800;
        return true;
    }
}

evalSize();
drawLarge();

/* Hightlight menu items (:hover does not quite handle both the text and the button, since the text is outside of it...)*/

const as = document.querySelectorAll(".large_menu_item a");
console.log(as);
for(const a of as) {
    a.addEventListener("mouseenter", evt => {
        console.log(evt.target);
        evt.target.closest(".large_menu_item").classList.add("large_menu_item_active");
    });
    a.addEventListener("mouseleave", evt => evt.target.closest(".large_menu_item").classList.remove("large_menu_item_active"));
}