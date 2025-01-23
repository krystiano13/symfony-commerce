import { Layout } from "../components/Layout";
import { useState, useMemo, useCallback, useEffect, useRef } from "react";
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
import { Toast } from 'primereact/toast';
import { ConfirmPopup } from 'primereact/confirmpopup';
import { confirmPopup } from 'primereact/confirmpopup';
import { Skeleton } from 'primereact/skeleton';

const Main = styled.main`
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 1rem;
`;

export default function Admin(props) {
    const [products, setProducts] = useState([]);
    const [orders] = useState([]);
    const [tabIndex, setTabIndex] = useState(0);
    const [createFormOpen, setCreateFormOpen] = useState(false);
    const [editFormOpen, setEditFormOpen] = useState(false);
    const [selectedFile, setSelectedFile] = useState(null);
    const [productsLoading, setProductsLoading] = useState(false);
    const [selectedProduct, setSelectedProduct] = useState(-1);

    const toastRef = useRef(null);

    const menuItems = useMemo(() => [
        { label: "Lista Produktów" },
        { label: "Zamówienia" }
    ], []);

    const productAction = useCallback((id) => {
        function handleClick(event) {
            confirmPopup({
                target: event.currentTarget,
                message: "Czy jesteś tego pewien ?",
                icon: 'pi pi-exclamation-triangle',
                acceptLabel: "Tak",
                rejectLabel: "Nie",
                accept: () => handleDelete(id),
                reject: () => {}
            });
        }

        return (
            <section style={{ display: "flex", alignItems: "center", gap: ".5rem" }}>
                <Button
                    onClick={() => {
                    setEditFormOpen(true)
                    setSelectedProduct(id)
                }}
                >
                    Edytuj
                </Button>
                <Button
                    onClick={handleClick}
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

    function getProducts() {
        setProductsLoading(true);
        fetch('/products', { method: "GET" })
            .then(res => res.json())
            .then(data => {
                setProducts([...data.products]);
                setProductsLoading(false);
            })
    }

    async function handleCreate(e, id = -1) {
        e.preventDefault();
        const data = new FormData(e.target);

        data.append("image", selectedFile);
        console.log(createFormOpen ? '/products' : `/products/${id}`)

        await fetch(createFormOpen ? '/products' : `/products/${id}`, {
            method: "POST",
            body: data
        })
            .then(res => {
                setCreateFormOpen(false);
                if(res.ok) {
                    getProducts();
                    toastRef.current.show({
                        severity:'success',
                        summary: 'Sukces',
                        detail:'Produkt utworzony',
                        life: 3000
                    });
                }
                else {
                    toastRef.current.show({
                        severity:'error',
                        summary: 'Błąd',
                        detail:'Coś poszło nie tak',
                        life: 3000
                    });
                }
                return res.json();
            })
            .then(data => {
                console.log(data)
            })
    }

    async function handleDelete(id) {
        await fetch(`/products/${id}`, { method: "DELETE" })
            .then(res => {
                getProducts();
            })
    }

    useEffect(() => {
      getProducts();
    }, [])

    return (
        <Layout user={props.user}>
            <Toast ref={toastRef} />
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
                    encType="multipart/form-data"
                    onSubmit={(e) => handleCreate(e, selectedProduct)}
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
                        onSelect={(e) => {
                            setSelectedFile(e.files[0])
                        }}
                        mode="basic"
                        accept="image/*"
                        style={{ width: "100%" }}
                        chooseLabel="Wybierz obrazek"
                        cancelLabel="Usuń obrazek"
                        uploadLabel="Wyślij obrazek"
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
                        {
                            productsLoading &&
                            <>
                                <Skeleton height="3rem" style={{ marginBottom: ".5rem" }}></Skeleton>
                                <Skeleton height="3rem" style={{ marginBottom: ".5rem" }}></Skeleton>
                                <Skeleton height="3rem" style={{ marginBottom: ".5rem" }}></Skeleton>
                                <Skeleton height="3rem" style={{ marginBottom: ".5rem" }}></Skeleton>
                                <Skeleton height="3rem" style={{ marginBottom: ".5rem" }}></Skeleton>
                                <Skeleton height="3rem" style={{ marginBottom: ".5rem" }}></Skeleton>
                                <Skeleton height="3rem" style={{ marginBottom: ".5rem" }}></Skeleton>
                                <Skeleton height="3rem" style={{ marginBottom: ".5rem" }}></Skeleton>
                                <Skeleton height="3rem" style={{ marginBottom: ".5rem" }}></Skeleton>
                            </>
                        }
                        {
                            !productsLoading &&
                            <>
                                <DataTable value={products}>
                                    <Column field="name" header="Nazwa Produktu"></Column>
                                    <Column field="price" header="Cena"></Column>
                                    <Column field="amount" header="Ilość w magazynie"></Column>
                                    <Column header="Akcje" body={(rowData) => productAction(rowData.id)}></Column>
                                </DataTable>
                                <Button
                                    onClick={() => setCreateFormOpen(true)}
                                    style={{ marginTop: "2rem" }}
                                >
                                    Dodaj Nowy Produkt
                                </Button>
                            </>
                        }
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