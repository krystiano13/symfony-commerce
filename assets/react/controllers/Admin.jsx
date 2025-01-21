import { Layout } from "../components/Layout";
import { useState, useMemo, useCallback } from "react";
import styled from "styled-components";
import { TabMenu } from "primereact/tabmenu";
import { Card } from "primereact/card";
import { DataTable } from "primereact/datatable";
import { Column } from "primereact/column";
import { Button } from "primereact/button";

const Main = styled.main`
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 1rem;
`;

export default function Admin(props) {
    const [products] = useState(JSON.parse(props.products));
    const [tabIndex, setTabIndex] = useState(0);

    const menuItems = useMemo(() => [
        { label: "Lista Produktów" },
        { label: "Zamówienia" }
    ], []);

    const productAction = useCallback(() => {
        return (
            <section style={{ display: "flex", alignItems: "center", gap: ".5rem" }}>
                <Button>Edytuj</Button>
                <Button severity="danger">Usuń</Button>
            </section>
        )
    }, []);

    return (
        <Layout user={props.user}>
            <TabMenu
                style={{ marginTop: "1rem" }}
                model={menuItems}
                activeIndex={tabIndex}
                onTabChange={(e) => setTabIndex(e.index)}
            />

            <Main>
                { tabIndex == 0 && <>
                    <Card style={{ width: "100%" }}>
                        <DataTable value={products}>
                            <Column field="name" header="Nazwa Produktu"></Column>
                            <Column field="price" header="Cena"></Column>
                            <Column field="amount" header="Ilość w magazynie"></Column>
                            <Column header="Akcje" body={productAction}></Column>
                        </DataTable>
                        <Button style={{ marginTop: "2rem" }}>
                            Dodaj Nowy Produkt
                        </Button>
                    </Card>
                </> }
                { tabIndex == 1 && <Card></Card>  }
            </Main>
        </Layout>
    )
}