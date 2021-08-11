let modalQt = 1;

const c = (el) => document.querySelector(el);
const n = (el) => document.getElementsByName(el);
const cs = (el) => document.querySelectorAll(el);

async function requestQueue() {
    const url = BASE_SITE + '/ajax/business/orders';
    const r = await fetch(url);
    const queue = await r.json();

    c('.queue').innerHTML = '';

    if (queue !== '') {
        queue.map((item, index) => {

            let queueItem = c(".model .card").cloneNode(true);

            queueItem.querySelector('.name_contact').innerHTML = item.contact_name;
            queueItem.querySelector('.id_order').innerHTML = ' #' + item.id_order;
            queueItem.querySelector('.address').innerHTML = item.address + ", " + item.address_number + " " + item.address_complement + ", " + item.neighborhood + ", " + item.city + " - " + item.state;
            queueItem.querySelector('.status').innerHTML = item.status_badge;
            if (item.status === 'free' || item.status === 'waiting_deliveryman_accept') {
                let footer = queueItem.querySelector('.footer');
                footer.classList.remove('d-none');

                queueItem.querySelector('.update_modal').addEventListener('click', (e) => {

                    c('.u_id').value = item.id;
                    c('.u_contact_name').value = item.contact_name;
                    c('.u_id_order').value = item.id_order;
                    c('.u_phone').value = item.phone;
                    c('.u_address').value = item.address;
                    c('.u_address_number').value = item.address_number;
                    c('.u_address_complement').value = item.address_complement;
                    c('.u_neighborhood').value = item.neighborhood;
                    c('.u_price').value = item.price;
                    c('.u_note').innerHTML = item.note;
                    c('.u_state').value = item.state;
                    let city = c('.u_city');
                    let option = document.createElement("option");
                    option.text = item.city;
                    option.selected = "selected";
                    city.add(option);
                    if (item.deliveryman_cod !== '') {
                        c('.u_deliveryman').value = item.deliveryman_cod;
                    }
                });

                queueItem.querySelector('.cancel_order').addEventListener('click', (e) => {
                    c('.d_id').value = item.id;
                    c('.d_id_order').innerHTML = '#' + item.id_order;
                });
            }
            if (item.status !== 'free') {
                let footer = queueItem.querySelector('.footer_deliveryman');
                footer.classList.remove("d-none");
                if (item.deliveryman_name !== '') {
                    queueItem.querySelector('.deliveryman_name').innerHTML = item.deliveryman_name;
                }
                if (item.deliveryman_whatsapp !== '') {
                    queueItem.querySelector('.deliveryman_whatsapp').innerHTML = ' <i class="flaticon-whatsapp text-success"></i> ' + item.deliveryman_whatsapp;
                    queueItem.querySelector('.deliveryman_whatsapp').setAttribute('href', item.deliveryman_link_whatsapp);
                }
            }

            c('.queue').append(queueItem);

        });
    }


}

requestQueue();