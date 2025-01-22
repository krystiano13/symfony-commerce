import { Layout } from "../components/Layout";
import { useState, useMemo, useCallback } from "react";
import styled from "styled-components";
import { TabMenu } from "primereact/tabmenu";
import { Card } from "primereact/card";
import { DataTable } from "primereact/datatable";
import { Column } from "primereact/column";
import { Button } from "primereact/button";
import { Dialog } from 'primereact/dialog';
import { InputText } from 'primereact/inputtext';
import { InputNumber } from 'primereact/inputnumber';
import { FileUpload } from "primereact/fileupload";
import { ConfirmPopup } from 'primereact/confirmpopup'; // To use <ConfirmPopup> tag
import { confirmPopup } from 'primereact/confirmpopup'; // To use confirmPopup method

const Main = styled.main`
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 1rem;
`;

export default function Admin(props) {
    const [products] = useState(JSON.parse(props.products));
    const [orders] = useState([]);
    const [tabIndex, setTabIndex] = useState(0);
    const [createFormOpen, setCreateFormOpen] = useState(false);
    const [editFormOpen, setEditFormOpen] = useState(false);

    const menuItems = useMemo(() => [
        { label: "Lista Produktów" },
        { label: "Zamówienia" }
    ], []);

    const productAction = useCallback(() => {
        function handleDelete(event) {
            confirmPopup({
                target: event.currentTarget,
                message: "Czy jesteś tego pewien ?",
                icon: 'pi pi-exclamation-triangle',
                acceptLabel: "Tak",
                rejectLabel: "Nie",
                accept: () => {},
                reject: () => {}
            });
        }

        return (
            <section style={{ display: "flex", alignItems: "center", gap: ".5rem" }}>
                <Button onClick={() => setEditFormOpen(true)}>Edytuj</Button>
                <Button
                    onClick={handleDelete}
                    className="p-button-danger"
                    severity="danger"
                >
                    Usuń
                </Button>
            </section>
        )
    }, []);

    const orderAction = useCallback(() => {
        return (
            <Button>Oznacz jako zrealizowany</Button>
        )
    }, [])

    return (
        <Layout user={props.user}>
            <ConfirmPopup />
            <Dialog
                header={ editFormOpen ? "Edytuj Produkt" : "Stwórz nowy produkt" }
                style={{ width: "30rem" }}
                visible={createFormOpen || editFormOpen}
                onHide={() => {
                    setCreateFormOpen(false)
                    setEditFormOpen(false)
                }}
            >
                <form
                    style={{
                        display: "flex",
                        flexDirection: "column",
                        alignItems: "center",
                        gap: ".5rem",
                        width: "100%"
                    }}
                >
                    <InputText
                        name="name"
                        required
                        placeholder="Nazwa Produktu"
                        style={{ width: "100%" }}
                    />
                    <InputNumber
                        name="price"
                        required
                        min={0.01}
                        placeholder="Cena"
                        style={{ width: "100%" }}
                    />
                    <InputNumber
                        name="amount"
                        required
                        min={0}
                        placeholder="Ilość"
                        style={{ width: "100%" }}
                    />
                    <FileUpload
                        name="imageSrc"
                        mode="basic"
                        accept="image/*"
                        style={{ width: "100%" }}
                        chooseLabel="Wybierz obrazek"
                        cancelLabel="Usuń obrazek"
                        uploadLabel="Wyślij obrazek"
                        required
                    />
                    <Button
                        style={{
                            width: "100%",
                            display: "flex",
                            justifyContent: "center",
                            alignItems: "center"
                        }}
                        type="submit"
                        severity="secondary"
                    >
                        { editFormOpen ? "Zapisz" : "Dodaj" }
                    </Button>
                </form>
            </Dialog>

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
                        <Button
                            onClick={() => setCreateFormOpen(true)}
                            style={{ marginTop: "2rem" }}
                        >
                            Dodaj Nowy Produkt
                        </Button>
                    </Card>
                </> }
                { tabIndex == 1 && <>
                    <Card style={{ width: "100%" }}>
                        <DataTable value={orders}>
                            <Column header="Imię"></Column>
                            <Column header="Nazwisko"></Column>
                            <Column header="Adres"></Column>
                            <Column header="Kod Pocztowy"></Column>
                            <Column header="Cena"></Column>
                            <Column header="Akcje" body={orderAction}></Column>
                        </DataTable>
                    </Card>
                </>  }
            </Main>
        </Layout>
    )
}