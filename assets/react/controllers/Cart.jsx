import { Layout } from "../components/Layout";
import { DataTable } from "primereact/datatable";
import { Column } from "primereact/column";
import { Card } from "primereact/card";
import { Button } from "primereact/button";
import { InputText } from "primereact/inputtext";
import { InputMask } from 'primereact/inputmask';
import styled from "styled-components";

const Main = styled.main`
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;

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
    const actionColumn = () => {
        return (
            <div>
                <Button>
                    Usuń z koszyka
                </Button>
            </div>
        )
    }

    const elements = [
        { name: "test", price: 457.99, amount: 1, actions: "" },
        { name: "test 2", price: 657.99, amount: 3, actions: "" },
        { name: "test", price: 457.99, amount: 1, actions: "" },
        { name: "test 2", price: 657.99, amount: 3, actions: "" },
        { name: "test", price: 457.99, amount: 1, actions: "" },
        { name: "test 2", price: 657.99, amount: 3, actions: "" }
    ];

    return (
        <Layout user={props.user}>
            <Main>
                <DataTable value={elements} style={{ marginTop: "1rem" }}>
                    <Column field="name" header="Nazwa Produktu"></Column>
                    <Column field="price" header="Cena"></Column>
                    <Column field="amount" header="Ilość"></Column>
                    <Column field="actions" body={actionColumn} header="Akcje"></Column>
                </DataTable>
                {
                    props.user.id !== -1 &&
                    <Card
                        title="Przejdź do transakcji"
                    >
                        <form
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
                            <Button>
                                Złóż zamówienie
                            </Button>
                        </form>
                    </Card>
                }
            </Main>
        </Layout>
    )
}