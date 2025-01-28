import { useState, useEffect } from "react";
import { Layout } from "../components/Layout";
import { DataTable } from "primereact/datatable";
import { Column } from "primereact/column";
import { Card } from "primereact/card";
import { Button } from "primereact/button";
import { InputText } from "primereact/inputtext";
import { InputMask } from 'primereact/inputmask';
import { Skeleton } from "primereact/skeleton";
import styled from "styled-components";

const Main = styled.main`
    width: 100%;
    display: flex;
    justify-content: space-between;

    gap: 1rem;
    
    .p-datatable {
        width : 60%;
    }
    
    .p-card {
        width : 40%;
        height: 100%;
    }
    
    @media screen and (max-width: 1023px) {
        flex-direction: column;
        justify-content: flex-start;
        
        .p-datatable, .p-card {
            width : 100%;
        }
    }
`;

export default function Cart(props) {
    const [cartItems, setCartItems] = useState([]);
    const [loading, setLoading] = useState(false);
    function getCart() {
        setLoading(true);
        fetch('/cart/get')
            .then(res => res.json())
            .then(data => {
                if(data.cart) {
                    setCartItems([...data.cart])
                }
                setLoading(false);
            })
    }

    async function handleSubmit(e) {
        e.preventDefault();

        const formData = new FormData(e.target);

        let fullPrice = 0;

        cartItems.forEach(item => {
            fullPrice += item.price;
        })

        const body = {
            name: formData.get("name"),
            surname: formData.get("surname"),
            town: formData.get("town"),
            postal_code: formData.get("postal_code"),
            address: formData.get("address"),
            full_price: fullPrice,
            status: "Not sent",
            products: JSON.stringify(cartItems)
        };

        await fetch(`/order`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(body)
        })
            .then(res => {
                if(res.ok) {
                    //window.location.reload();
                }
                console.log(res.status);
                return res.json();
            })
            .then(data => {
                console.log(data);
            })
    }

    const actionColumn = (id) => {
        async function handleClick() {
            await fetch(`/cart/${id}`, {
                method: "DELETE"
            })
                .then(res => res.json())
                .then(() => {
                    getCart();
                })
        }

        return (
            <div>
                <Button onClick={handleClick}>
                    Usuń z koszyka
                </Button>
            </div>
        )
    }

    useEffect(() => {
        getCart();
    }, [])

    return (
        <Layout user={props.user}>
            <Main>
                {
                    loading === false && (
                        <DataTable value={cartItems} style={{ marginTop: "1rem" }}>
                            <Column field="name" header="Nazwa Produktu"></Column>
                            <Column field="price" header="Cena"></Column>
                            <Column field="amount" header="Ilość"></Column>
                            <Column field="actions" body={(rowData) => actionColumn(rowData.id)} header="Akcje"></Column>
                        </DataTable>
                    )
                }
                {
                    loading === true &&
                    <Card style={{
                        width: "66%",
                        marginTop: "1rem",
                        display: "flex",
                        flexDirection: "column",
                        gap: ".5rem"
                    }}>
                        <Skeleton height="3rem"/>
                        <Skeleton height="3rem"/>
                        <Skeleton height="3rem"/>
                        <Skeleton height="3rem"/>
                    </Card>
                }
                {
                    props.user.id !== -1 && cartItems.length > 0 &&
                    <Card
                        title="Przejdź do transakcji"
                        style={{marginTop: "1rem"}}
                    >
                        <form
                            onSubmit={handleSubmit}
                            style={{
                                display: "flex",
                                flexDirection: "column",
                                gap: "1rem"
                            }}
                        >
                            <section
                                style={{
                                    display: "flex",
                                    gap: ".5rem"
                                }}
                            >
                                <InputText
                                    name="name"
                                    placeholder="Imię"
                                    required={true}
                                    style={{ width: "50%" }}
                                />
                                <InputText
                                    name="surname"
                                    placeholder="Nazwisko"
                                    required={true}
                                    style={{ width: "50%" }}
                                />
                            </section>
                            <section
                                style={{
                                    display: "flex",
                                    gap: ".5rem"
                                }}
                            >
                                <InputMask
                                    mask="99-999"
                                    placeholder="Kod Pocztowy"
                                    name="postal_code"
                                    required={true}
                                    style={{ width: "50%" }}
                                />
                                <InputText
                                    name="town"
                                    placeholder="Miejscowość"
                                    required={true}
                                    style={{ width: "50%" }}
                                />
                            </section>
                            <InputText
                                name="address"
                                placeholder="Ulica"
                                required={true}
                            />
                            <Button type="submit">
                                Złóż zamówienie
                            </Button>
                        </form>
                    </Card>
                }
            </Main>
        </Layout>
    )
}