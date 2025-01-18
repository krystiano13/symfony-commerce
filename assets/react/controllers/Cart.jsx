import { Layout } from "../components/Layout";
import { DataTable } from "primereact/datatable";
import { Column } from "primereact/column";
import { Button } from "primereact/button";

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
            <DataTable value={elements} style={{ marginTop: "1rem" }}>
                <Column field="name" header="Nazwa Produktu"></Column>
                <Column field="price" header="Cena"></Column>
                <Column field="amount" header="Ilość"></Column>
                <Column field="actions" body={actionColumn} header="Akcje"></Column>
            </DataTable>
        </Layout>
    )
}