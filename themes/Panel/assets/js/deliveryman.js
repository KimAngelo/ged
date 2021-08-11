let modalQt = 1;

const c = (el) => document.querySelector(el);
const n = (el) => document.getElementsByName(el);
const cs = (el) => document.querySelectorAll(el);

async function requestQueue() {
    const url = BASE_SITE + '/ajax/deliveryman/orders';
    const r = await fetch(url);
    const queue = await r.json();

    c('.queue').innerHTML = '';

    if (queue !== '') {
        queue.map((item, index) => {

            let queueItem = c(".model .loop").cloneNode(true);

            queueItem.querySelector('.name').innerHTML = item.name;
            queueItem.querySelector('.neighborhood').innerHTML = item.neighborhood;
            queueItem.querySelector('.city').innerHTML = item.city;
            queueItem.querySelector('.price').innerHTML = item.price;
            queueItem.querySelector('a').addEventListener('click', (e) => {
                c(".m_name").innerHTML = item.name;
                c(".m_address_business").innerHTML = item.address_business;
                c(".m_address_delivery").innerHTML = item.address_delivery;
                c(".m_price").innerHTML = item.price;
                c(".m_note").innerHTML = item.note;
                c(".id_order").value = item.id;
            });


            c('.queue').append(queueItem);

        });
    }


}

requestQueue();

channel.bind('deliveryman', function (data) {
    requestQueue();
});