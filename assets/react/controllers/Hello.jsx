import React from 'react';
import { Layout } from "../components/Layout";
import { Button } from 'primereact/button';

export default function (props) {
    return (
        <Layout>
            <div>Hello {props.fullName}</div>
            <Button>Hello</Button>
        </Layout>
    )
}
